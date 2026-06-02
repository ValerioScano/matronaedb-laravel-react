<?php

namespace Database\Seeders;

use App\Models\Filing;
use App\Models\Proposal;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class TaggableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 1; $i < 1000; $i++) {
            Filing::find($i)->tags()->attach($faker->randomElements(range(1, 30)));
        }
        for ($i = 1; $i <= 500; $i++) {
            Proposal::find($i)->tags()->attach($faker->randomElements(range(1, 30)));
        }
    }
}
