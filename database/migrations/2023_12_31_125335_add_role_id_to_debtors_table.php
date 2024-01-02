<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleIdToDebtorsTable extends Migration
{
    public function up()
    {
        Schema::table('debtors', function (Blueprint $table) {
            // Add a new unsigned integer column 'role_id'
            $table->unsignedBigInteger('role_id')->nullable();

            // Add a foreign key constraint linking 'role_id' to 'id' column in 'roles' table
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    public function down()
    {
        Schema::table('debtors', function (Blueprint $table) {
            // Drop the foreign key constraint and the 'role_id' column
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
}
