<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('compagnies', function (Blueprint $table) {
            $table->id();

            // Informations principales
            $table->string('nom');
            $table->string('logo')->nullable();
            $table->text('description')->nullable();

            // Coordonnées
            $table->string('telephone')->nullable();
            $table->string('email')->unique();
            $table->string('adresse')->nullable();
            $table->string('site_web')->nullable();
            $table->boolean('profil_complet')->default(false);

            // Accès et sécurité
            $table->string('CMPID')->unique(); // Identifiant unique (généré automatiquement)
            $table->string('mot_de_passe');               // Mot de passe hashé (envoyé par mail)

            // Relation avec type de catégorie (transport, santé, etc.)
            $table->foreignId('type_categorie_id')->nullable()->constrained('type_categories')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compagnies');
    }
};
