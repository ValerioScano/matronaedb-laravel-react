<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Edition;
use Faker\Generator as Faker;

class EditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {   
        $corpus = ['CIL', 'Année épigraphique', 'ZPE'];

        for($i=0; $i<1500; $i++) {
            $newEdition= new Edition();
            $newEdition->corpus=$faker->randomElement($corpus);
            $newEdition->volume=$faker->boolean(50) ? $faker->randomDigit() : null;
            $newEdition->number_inscription=$faker->boolean(80) ? $faker->numberBetween(1, 2000) : null;
            $newEdition->publication_year=$faker->boolean(20) ? $faker->numberBetween(1800, 2000) : null;
            $newEdition->corpus_page= $newEdition->publication_year !== null ? $faker->numberBetween(1, 1000) : null;
            $newEdition->last_name_author= $newEdition->publication_year !== null ? $faker->lastName() : null;
            $newEdition->filing_id = $faker->numberBetween(1, 1000);
            $newEdition->save();
        }
    }
}