<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users(){
		return $this->belongsToMany(User::class, 'role_user', 'user_id', 'role_id');
    }
}
