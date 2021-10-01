<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_modulos', function (Blueprint $table) {
            $table->id(column: 'id_item_modulo');
            $table->integer(column: 'item_modulo');
            $table->string(column: 'nombre_item_modulo');
            $table->string(column: 'url_item_modulo');
            $table->string(column: 'icono_item_modulo');
            $table->integer(column: 'orden');
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
        Schema::dropIfExists('item_modulos');
    }
}
