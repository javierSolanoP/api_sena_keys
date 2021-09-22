<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmbientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ambientes', function (Blueprint $table) {
            $table->id(column: 'id_ambiente');
            $table->unsignedBigInteger(column: 'zona_id');
            $table->string(column: 'nombre_ambiente', length: 100);
            $table->string(column: 'description', length: 100);
            $table->string(column: 'estado', length: 100);
            $table->foreign('zona_id')->references('id_zona')->on('zonas');
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
        Schema::dropIfExists('ambientes');
    }
}
