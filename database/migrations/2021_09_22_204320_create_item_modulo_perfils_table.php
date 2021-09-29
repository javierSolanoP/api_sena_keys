<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemModuloPerfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_modulo_perfils', function (Blueprint $table) {
            $table->id('id_item_modulo_perfil');
            $table->unsignedBigInteger(column: 'item_modulo_id');
            $table->unsignedBigInteger(column: 'perfil_id');
            $table->foreign('item_modulo_id')->references('id_item_modulo')->on('item_modulos')->onDelete('cascade');
            $table->foreign('perfil_id')->references('id_perfil')->on('perfils')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_modulo_perfils');
    }
}
