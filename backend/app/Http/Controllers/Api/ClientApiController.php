<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommandeResource;
use App\Models\Commande;
use App\Services\DeliveryFee;
use Illuminate\Http\Request;

class ClientApiController extends Controller
{
    // Uniquement les commandes du client connecté
    public function index(Request $request)
    {
        $client = $request->user()->client;
        abort_unless($client, 403, 'Profil client introuvable.');

        $commandes = Commande::with(['client', 'chauffeur'])
            ->where('client_id', $client->id)
            ->latest()->get();

        return CommandeResource::collection($commandes);
    }

    public function store(Request $request)
    {
        $client = $request->user()->client;
        abort_unless($client, 403, 'Profil client introuvable.');

        $data = $request->validate([
            'store' => 'required|string',
            'items' => 'nullable|array|max:50',
            'items.*.id' => 'required|string',
            'items.*.qty' => 'required|integer|min:1|max:20',
            'request_text' => 'nullable|string|max:2000',
            'adresse_livraison' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $store = collect(config('catalog.categories'))->firstWhere('id', $data['store']);
        abort_unless($store, 422, 'Magasin inconnu.');

        // Les prix viennent du catalogue serveur, jamais du navigateur
        $details = ($store['type'] ?? 'menu') === 'free_order'
            ? $this->freeOrderDetails($store, $data)
            : $this->menuDetails($store, $data);

        if (!empty($data['notes'])) {
            $details .= "\nNote du client : ".$data['notes'];
        }

        $client->update(['telephone' => $data['telephone']]);

        $commande = Commande::create([
            'client_id' => $client->id,
            'date' => today(),
            'adresse_livraison' => $data['adresse_livraison'],
            'details' => $details,
            'statut' => Commande::EN_ATTENTE,
        ]);

        return (new CommandeResource($commande->load(['client', 'chauffeur'])))
            ->response()->setStatusCode(201);
    }

    private function menuDetails(array $store, array $data): string
    {
        $lines = [];
        $subtotal = 0;
        $hasMeal = false;

        foreach ($data['items'] ?? [] as $line) {
            $item = $this->findItem($store, $line['id']);
            abort_unless($item, 422, 'Article introuvable : '.$line['id']);

            $qty = (int) $line['qty'];
            $total = $item['price'] * $qty;
            $subtotal += $total;
            $hasMeal = $hasMeal || !empty($item['meal']);
            $lines[] = "{$qty} x {$item['name']} ({$item['price']} MAD) = {$total} MAD";
        }

        abort_if(empty($lines), 422, 'Votre panier est vide.');

        $fee = DeliveryFee::forOrder($subtotal, $hasMeal);

        return implode("\n", array_merge(
            ['Magasin : '.$store['name']],
            $lines,
            [
                "Sous-total : {$subtotal} MAD",
                "Livraison : {$fee} MAD",
                'TOTAL : '.($subtotal + $fee).' MAD (paiement à la livraison)',
            ]
        ));
    }

    private function freeOrderDetails(array $store, array $data): string
    {
        $text = trim($data['request_text'] ?? '');
        abort_if($text === '', 422, 'Décrivez ce dont vous avez besoin.');

        $fee = config('catalog.free_order_delivery', 10);

        return implode("\n", [
            'Magasin : '.$store['name'].' (commande libre)',
            'Demande du client :',
            $text,
            "Livraison : {$fee} MAD. Prix des produits à confirmer par la pharmacie (paiement à la livraison).",
        ]);
    }

    // id = "indexRayon-indexArticle", comme dans CatalogController
    private function findItem(array $store, string $id): ?array
    {
        if (!preg_match('/^(\d+)-(\d+)$/', $id, $m)) {
            return null;
        }

        return $store['options'][(int) $m[1]]['items'][(int) $m[2]] ?? null;
    }
}