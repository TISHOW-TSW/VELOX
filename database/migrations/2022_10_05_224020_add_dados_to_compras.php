<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDadosToCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->string('buscador')->nullable();
            $table->integer('status')->default(0);
            $table->double('valor')->default(0);
            $table->dateTime('dia_pagamento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropColumn('buscador');
            $table->dropColumn('status');
            $table->dropColumn('valor');
            $table->dropColumn('dia_pagamento');
        });
    }
}
