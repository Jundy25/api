<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['id' => 1, 'role' => 'Admin'],
            ['id' => 2, 'role' => 'Customer'],

        ];

        DB::table('items')->insert($items);
    }
}
