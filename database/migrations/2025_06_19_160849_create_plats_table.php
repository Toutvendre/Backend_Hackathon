<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compagnie_id')->constrained()->onDelete('cascade');
            $table->foreignId('categorie_plat_id')->constrained('categorie_plats')->onDelete('cascade');
            $table->string('nom');
            $table->decimal('prix', 10, 2);
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('disponibilite')->default(true);
            $table->string('temps_preparation')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plats');
    }
};
