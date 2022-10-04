<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanoVantagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plano_vantagem', function (Blueprint $table) {
            $table->bigInteger('plano_id')->unsigned()->index();
            $table->foreign('plano_id')->references('id')->on('planos')->onDelete('cascade');
            $table->bigInteger('vantagem_id')->unsigned()->index();
            $table->foreign('vantagem_id')->references('id')->on('vantagems')->onDelete('cascade');
            $table->primary(['plano_id', 'vantagem_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plano_vantagem');
    }
}
