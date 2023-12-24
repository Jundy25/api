<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtorsTable extends Migration
{
    public function up()
    {
        Schema::create('debtors', function (Blueprint $table) {
            $table->id('d_id');
            $table->string('d_name')->unique();
            $table->string('phone');
            $table->string('address');
            $table->timestamps()->nullable()->default(now());
        });
    }

    public function down()
    {
        Schema::dropIfExists('debtors');
    }
};
