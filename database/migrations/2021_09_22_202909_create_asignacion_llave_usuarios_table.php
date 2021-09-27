<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignacionLlaveUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignacion_llave_usuarios', function (Blueprint $table) {
            $table->id(column: 'id_llave_usuario');
            $table->unsignedBigInteger(column: 'usuario_id')->nullable();
            $table->unsignedBigInteger(column: 'llave_id')->nullable();
            $table->date(column: 'entregada_el');
            $table->string(column: 'en_uso', length: 100)->nullable();
            $table->date(column: 'regresada_el')->nullable();
            $table->foreign('usuario_id')->references('id_usuario')->on('users')->onDelete('set null');
            $table->foreign('llave_id')->references('id_llave')->on('llaves')->onDelete('set null');
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
        Schema::dropIfExists('asignacion_llave_usuarios');
    }
}
