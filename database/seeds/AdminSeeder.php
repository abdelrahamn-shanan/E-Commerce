<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
        'name' => "Abdoshanan",
        'email' => "01012617633abdoshanan@gmail.com",
        'password' => bcrypt("3450326"),
        'Mobile' => "3450326",
        ]) ;

    }
}
