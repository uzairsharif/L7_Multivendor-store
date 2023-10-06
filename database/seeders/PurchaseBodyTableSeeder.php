<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PurchaseBodyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('purchase_body')->insert([
         	'id' => '1',
            'purchase_head_id' => '1',
            'product_id' => '1',
            'user_id' => '1',
            
            'product_purchase_qty' => '100',
            'product_purchase_rate' => '100',
            'product_sale_price' => '140',
            'created_at' => now(),
            'updated_at' => now(),                       
        ]);
        DB::table('purchase_body')->insert([
         	'id' => '2',
            'purchase_head_id' => '1',
            'product_id' => '2',
            'user_id' => '1',
            
            'product_purchase_qty' => '100',
            'product_purchase_rate' => '100',
            'product_sale_price' => '140',
            'created_at' => now(),
            'updated_at' => now(),                       
        ]);
        DB::table('purchase_body')->insert([
         	'id' => '3',
            'purchase_head_id' => '2',
            'product_id' => '1',
            'user_id' => '2',
            
            'product_purchase_qty' => '100',
            'product_purchase_rate' => '100',
            'product_sale_price' => '140',
            'created_at' => now(),
            'updated_at' => now(),                       
        ]);
    }
}
