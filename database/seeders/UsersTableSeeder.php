<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'), // Hash the password using Bcrypt
                'role' => 1,
                'created_at' => now(),
            ],
        ];

        DB::table('users')->insert($items);
    }
}
