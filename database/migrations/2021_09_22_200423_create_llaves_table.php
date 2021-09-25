<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLlavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('llaves', function (Blueprint $table) {
            $table->id(column: 'id_llave');
            $table->unsignedBigInteger(column: 'ambiente_id');
            $table->string(column: 'url_codigo_qr');
            $table->string(column: 'codigo_llave', length: 20);
            $table->foreign('ambiente_id')->references('id_ambiente')->on('ambientes')->onDelete('cascade');
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
        Schema::dropIfExists('llaves');
    }
}
