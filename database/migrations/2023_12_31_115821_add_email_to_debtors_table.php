<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToDebtorsTable extends Migration
{
    public function up()
    {
        Schema::table('debtors', function (Blueprint $table) {
            $table->string('email')->after('d_name')->nullable(); // Change the datatype and other constraints if needed
        });
    }

    public function down()
    {
        Schema::table('debtors', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
}

