<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientOrdersTest extends TestCase
{
    use RefreshDatabase;

    private function makeClient(string $email): Client
    {
        $user = User::create([
            'name' => 'Client '.$email,
            'email' => $email,
            'password' => 'password',
            'role' => 'client',
        ]);

        return Client::create([
            'user_id' => $user->id,
            'nom' => $user->name,
            'telephone' => '0600000000',
        ]);
    }

    private function makeOrder(Client $client): Commande
    {
        return Commande::create([
            'client_id' => $client->id,
            'date' => today(),
            'adresse_livraison' => '12 Rue Test',
            'statut' => 'en_attente',
        ]);
    }

    public function test_client_only_sees_own_orders(): void
    {
        $clientA = $this->makeClient('a@test.com');
        $clientB = $this->makeClient('b@test.com');
        $orderA = $this->makeOrder($clientA);
        $orderB = $this->makeOrder($clientB);

        Sanctum::actingAs($clientA->user);

        $response = $this->getJson('/api/client/commandes')->assertOk();

        $response->assertJsonCount(1, 'data');
        $ids = collect($response->json('data'))->pluck('id');
        $this->assertTrue($ids->contains($orderA->id));
        $this->assertFalse($ids->contains($orderB->id));
    }

    public function test_client_cannot_use_driver_routes(): void
    {
        $client = $this->makeClient('a@test.com');

        Sanctum::actingAs($client->user);

        $this->getJson('/api/mes-livraisons')->assertForbidden();
    }

    public function test_guest_cannot_see_client_orders(): void
    {
        $this->getJson('/api/client/commandes')->assertUnauthorized();
    }
}