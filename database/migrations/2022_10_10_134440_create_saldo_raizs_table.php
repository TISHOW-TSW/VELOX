<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaldoRaizsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldo_raizs', function (Blueprint $table) {
            $table->id();
            $table->float('valor');
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreignId('compra_id');
            $table->foreign('compra_id')->references('id')->on('compras');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saldo_raizs');
    }
}
