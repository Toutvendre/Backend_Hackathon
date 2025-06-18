<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type_transport');
            $table->string('type_trajet');
            $table->string('ville_depart');
            $table->string('ville_arrivee');
            $table->time('heure_depart');
            $table->time('heure_arrivee');
            $table->decimal('prix', 8, 2);
            $table->string('localisation')->nullable();
           $table->foreignId('compagnie_id')->nullable()->constrained('compagnies')->nullOnDelete();
            
        });
    }

    public function down() {
        Schema::dropIfExists('voyages');
    }
};
