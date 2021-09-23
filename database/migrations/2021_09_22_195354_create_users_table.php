<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(column: 'id_usuario');
            $table->unsignedBigInteger(column: 'perfil_id');
            $table->string(column: 'identificacion');
            $table->string(column: 'nombre');
            $table->string(column: 'apellido');
            $table->string(column: 'codigo_barras');
            $table->string(column: 'email')->unique();
            $table->string(column: 'password');
            $table->foreign('perfil_id')->references('id_perfil')->on('perfils');
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
        Schema::dropIfExists('users');
    }
}
