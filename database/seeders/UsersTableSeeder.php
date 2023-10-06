<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'uzair',
            'email' => 'uzair@uzair.com',
            'password' => Hash::make('password'),
        ]);
        DB::table('users')->insert([
            'name' => 'ayesha',
            'email' => 'ayesha@ayesha.com',
            'password' => Hash::make('password'),
        ]);
    }
}
