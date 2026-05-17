<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
       
        for($i = 0; $i<100; $i++) {
            $newUser= new User();
            $newUser->first_name = $faker->firstName();
            $newUser->last_name = $faker->lastName();
            $newUser->role = $faker->boolean(10) ? 'admin' : 'registered_user';
            $newUser->email = $faker->unique()->safeEmail();
            $newUser->password = bcrypt('password');
            $newUser->email_verified_at= $faker->dateTime();
            $newUser->save();
        }
    }
}
