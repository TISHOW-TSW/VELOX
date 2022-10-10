<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaqueraizsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saqueraizs', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor');
            $table->dateTime('data');
            $table->integer('status')->default(0);
            $table->bigInteger('user_id')->unsigned();
            $table->integer('meio_saque');
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
        Schema::dropIfExists('saqueraizs');
    }
}
