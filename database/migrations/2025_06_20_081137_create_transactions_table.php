<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_vetement_id')->constrained('commande_vetements')->onDelete('cascade');
            $table->string('code_transaction')->unique();
            $table->string('telephone');
            $table->decimal('montant', 10, 2);
            $table->string('otp');
            $table->enum('statut', ['en_attente', 'effectuee', 'échouée'])->default('en_attente');
            $table->timestamp('verifie_a')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
