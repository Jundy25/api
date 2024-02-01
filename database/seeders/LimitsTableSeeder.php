<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LimitsTableSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['id' => 1, 'type' => 'InventoryLimit', 'amount' => 3000, 'created_at' => now()],
            ['id' => 2, 'type' => 'LimitByDebtor', 'amount' => 1000, 'created_at' => now()],
            ['id' => 3, 'type' => 'Interest', 'amount' => 0.01, 'created_at' => now()],
        ];

        DB::table('limit')->insert($items);
    }
}
