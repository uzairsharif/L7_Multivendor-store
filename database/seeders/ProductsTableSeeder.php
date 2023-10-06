<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
         	'id' => '1',
            'user_id' => '1',
            'name' => 'u',
            'description' => 'u',
            
            'img_upload_url' => '/storage/user_1/product_1.jpg',
            'created_at' => now(),
            'updated_at' => now(),
            
            
            'category_id' => '2',
        ]);
         DB::table('products')->insert([
         	'id' => '2',
            'user_id' => '1',
            'name' => 'u2',
            'description' => 'u2',
            
            'img_upload_url' => '/storage/user_1/product_2.jpg',
            'created_at' => now(),
            'updated_at' => now(),
            
            'category_id' => '1',
        ]);
         DB::table('products')->insert([
         	'id' => '1',
            'user_id' => '2',
            'name' => 'a1',
            'description' => 'a1',
            
            'img_upload_url' => '/storage/user_2/product_1.jpg',
            'created_at' => now(),
            'updated_at' => now(),
            
            'category_id' => '2',
        ]);
    }
}
