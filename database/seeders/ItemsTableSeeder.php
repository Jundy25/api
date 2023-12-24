<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['item_id' => 1, 'item_name' => 'Rice', 'price' => 50.00, 'category' => 'Goods'],
            ['item_id' => 2, 'item_name' => 'Egg', 'price' => 10.00, 'category' => 'Goods'],
            ['item_id' => 3, 'item_name' => 'Bread', 'price' => 48.00, 'category' => 'Goods'],
            ['item_id' => 4, 'item_name' => 'Powdered Milk', 'price' => 12.00, 'category' => 'Powder'],
            ['item_id' => 5, 'item_name' => 'Softdrink', 'price' => 20.00, 'category' => 'Beverages'],
            ['item_id' => 6, 'item_name' => 'Juice', 'price' => 18.00, 'category' => 'Beverages'],
            ['item_id' => 7, 'item_name' => 'Coffee', 'price' => 15.00, 'category' => 'Powder'],
            ['item_id' => 8, 'item_name' => 'Sugar', 'price' => 28.00, 'category' => 'Powder'],
            ['item_id' => 9, 'item_name' => 'Bleach', 'price' => 45.00, 'category' => 'Cleaning Products'],
            ['item_id' => 10, 'item_name' => 'Soap', 'price' => 25.00, 'category' => 'Cleaning Products'],
            ['item_id' => 11, 'item_name' => 'Beer', 'price' => 120.00, 'category' => 'Alcohol'],
        ];

        DB::table('items')->insert($items);
    }
}
