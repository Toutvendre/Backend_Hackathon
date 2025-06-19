<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produit_vetements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compagnie_id')->constrained()->onDelete('cascade');
            $table->foreignId('vetement_categorie_id')->constrained('vetement_categories')->onDelete('cascade');
            $table->foreignId('vetement_sous_categorie_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->decimal('prix', 10, 2);
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produit_vetements');
    }
};
