<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquest\SoftDeletes;

class Customer extends Model
{
    // use SoftDeletes;
	protected $guarded = [];
	protected $dates = ['deleted_at'];
}
