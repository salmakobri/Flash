<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommandeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client' => $this->client->nom,
            'telephone' => $this->client->telephone,
            'adresse' => $this->adresse_livraison,
            'date' => $this->date->toDateString(),
            'statut' => $this->statut,
            'note' => $this->note,
            'details' => $this->details,
            'chauffeur' => $this->chauffeur?->nom,
        ];
    }
}