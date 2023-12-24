<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
{
    Schema::create('items', function (Blueprint $table) {
        $table->id('item_id');
        $table->string('item_name');
        $table->decimal('price', 8, 2);
        $table->string('category');
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('items');
    }
};
