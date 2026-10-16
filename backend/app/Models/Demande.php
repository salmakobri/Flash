<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    const RIDER = 'rider';
    const PARTNER = 'partner';

    const EN_ATTENTE = 'en_attente';
    const ACCEPTEE = 'acceptee';
    const REFUSEE = 'refusee';

    protected $fillable = [
        'type', 'nom', 'email', 'telephone', 'ville', 'vehicule', 'magasin',
        'categorie', 'message', 'password', 'statut', 'note_admin', 'user_id', 'traitee_le',
    ];

    protected $hidden = ['password'];

    protected $casts = ['traitee_le' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
}