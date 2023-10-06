<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PurchaseHeadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purchase_head')->insert([
         	'id' => '1',
            'payment_amount' => '200',
            'vendor_id' => '1',
            'created_at' => now(),
            'updated_at' => now(),                                 
        ]);
        DB::table('purchase_head')->insert([
         	'id' => '2',
            'payment_amount' => '300',
            'vendor_id' => '2',
            'created_at' => now(),
            'updated_at' => now(),                                 
        ]);
    }
}
