<?php

namespace Database\Factories;
use App\Role;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

class RolesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'name' => $faker->name,
         
    ];
    }
}
