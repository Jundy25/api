<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailySalesTable extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id('sale_id');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity_sold');
            $table->decimal('price', 8, 2);
            $table->date('sale_date');
            $table->string('debtor_name');
            $table->timestamps();
            $table->foreign('item_id')->references('item_id')->on('items');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_sales');
    }
}
