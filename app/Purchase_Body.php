<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Body extends Model
{
    use HasFactory;
    public function Product(){
    	return $this->hasMany(Product::class);
    }
}
