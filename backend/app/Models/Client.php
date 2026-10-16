<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['user_id', 'nom', 'telephone'];

    public function user() { return $this->belongsTo(User::class); }
    public function commandes() { return $this->hasMany(Commande::class); }
}