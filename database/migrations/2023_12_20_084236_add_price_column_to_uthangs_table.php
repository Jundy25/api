<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceColumnToUthangsTable extends Migration
{
    public function up()
    {
        Schema::table('uthangs', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('uthangs', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
}
