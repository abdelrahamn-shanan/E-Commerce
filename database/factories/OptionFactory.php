<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Option;
use Faker\Generator as Faker;

$factory->define(Option::class, function (Faker $faker) {
    $attributes = App\Models\Attribute::pluck('id')->toArray();
    $products = App\Models\Product::pluck('id')->toArray();

    return [
       
            'name' => $faker->text(60),
            'attribute_id' => $faker->randomElement($attributes),
            'product_id' => $faker->randomElement($products),
            'price' => $faker->numberBetween(10, 9000),
           
        ];
    
});
