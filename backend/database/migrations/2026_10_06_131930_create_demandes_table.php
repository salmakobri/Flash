<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('type');                       // rider | partner
            $table->string('nom');
            $table->string('email');
            $table->string('telephone', 20);
            $table->string('ville')->nullable();
            $table->string('vehicule')->nullable();       // livreur
            $table->string('magasin')->nullable();        // partenaire : nom du magasin
            $table->string('categorie')->nullable();      // partenaire : type de magasin
            $table->text('message')->nullable();
            $table->string('password')->nullable();       // livreur : mot de passe déjà hashé
            $table->string('statut')->default('en_attente');
            $table->text('note_admin')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('traitee_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};