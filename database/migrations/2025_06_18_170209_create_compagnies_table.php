<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('compagnies', function (Blueprint $table) {
            $table->id(); // clé primaire
            $table->string('nom'); // le nom de la compagnie
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down() {
        Schema::dropIfExists('compagnies');
    }
};
