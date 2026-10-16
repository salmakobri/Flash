<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    const EN_ATTENTE = 'en_attente';
    const AFFECTEE = 'affectee';
    const EN_COURS = 'en_cours';
    const LIVREE = 'livree';

    protected $fillable = ['client_id', 'chauffeur_id', 'date', 'adresse_livraison',
                           'statut', 'note', 'details'];

    protected $casts = ['date' => 'date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class);
    }
}