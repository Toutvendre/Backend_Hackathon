<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('type_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_categories');
    }
}
