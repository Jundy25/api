<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUthangsTable extends Migration
{
    public function up()
    {
        Schema::create('uthangs', function (Blueprint $table) {
            $table->id('u_id');
            $table->unsignedBigInteger('d_id');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->dateTime('added_on');
            $table->dateTime('updated_at');
            $table->foreign('item_id')->references('item_id')->on('items');
            $table->foreign('d_id')->references('d_id')->on('debtors')->onDelete('cascade')->onUpdate('cascade');
            // Add other columns if needed
        });
    }

    public function down()
    {
        Schema::dropIfExists('uthangs');
    }
};
