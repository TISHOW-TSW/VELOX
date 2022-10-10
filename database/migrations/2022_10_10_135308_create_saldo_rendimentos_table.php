<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaldoRendimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldo_rendimentos', function (Blueprint $table) {
            $table->id();
            $table->float('valor');
            $table->float('saque_rendimento');
            $table->foreignId('saldo_raiz_id');
            $table->foreign('saldo_raiz_id')->references('id')->on('saldo_raizs');
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
        Schema::dropIfExists('saldo_rendimentos');
    }
}
