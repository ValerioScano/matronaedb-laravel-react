<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Filing;
use Faker\Generator as Faker;

class FilingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
         
        
        for($i=0; $i<1000; $i++) {
            $year1 = $faker->numberBetween(-100, 700);
            $year2 = $faker->numberBetween(-100, 700);
            $newFiling= new Filing();
            $newFiling->text = $faker->paragraphs(4, true);
            $newFiling->region = $faker->country();
            $newFiling->province= $faker->state();
            $newFiling->city = $faker->city();
            $newFiling->min_year = min($year1, $year2);
            $newFiling->max_year = max($year1, $year2);
            $newFiling->is_certain_date = $faker->boolean(50);
            $newFiling->is_sacred_dedication = $faker->boolean(50);
            $newFiling->notes = $faker->boolean(25) ? $faker->paragraphs(4, true) : null;
            $newFiling->religion= $faker->randomElement(['pagan', 'uncertain', 'christian']);
            $newFiling->proposed_by = $faker->numberBetween(1, 100);
            $newFiling->approved_by = $faker->numberBetween(1, 100);
            $newFiling->save();
        }
    }
}
