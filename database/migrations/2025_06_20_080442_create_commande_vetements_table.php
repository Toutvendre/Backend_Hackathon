<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('commande_vetements', function (Blueprint $table) {
            $table->id();

            // Références
            $table->foreignId('vetement_produit_id')->constrained('produit_vetements')->onDelete('cascade');
            $table->foreignId('compagnie_id')->constrained('compagnies')->onDelete('cascade');

            // Informations client
            $table->string('client_nom');
            $table->string('client_telephone');

            // Informations de livraison
            $table->boolean('livraison')->default(false);
            $table->text('adresse_livraison')->nullable();
            $table->text('notes')->nullable();

            // Détails de la commande
            $table->unsignedInteger('quantite');
            $table->decimal('prix_total', 10, 2);

            // Statut de la commande
            $table->enum('statut', ['en_attente', 'en_cours', 'livrée', 'annulée'])->default('en_attente');

            // Numéro de reçu
            $table->string('numero_recu')->nullable();

            // Timestamps
            $table->timestamp('date_commande')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_vetements');
    }
};
