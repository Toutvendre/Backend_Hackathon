<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commande_plats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plat_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_total', 10, 2);
            $table->enum('statut', ['en_attente', 'en_livraison', 'livre'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_plats');
    }
};
