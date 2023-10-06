<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;
    // protected $dates = ['created_at'];
    // $guarded = [];
    public function Purchase_body(){
    	return $this->belongsTo(Purchase_body::class);
    }
}
