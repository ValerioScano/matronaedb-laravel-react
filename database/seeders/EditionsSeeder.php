<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filing;
use App\Models\Proposal;
use Faker\Generator as Faker;

class EditionsSeeder extends Seeder
{
    public function run(Faker $faker): void
    {
        $corpus = ['CIL', 'Année épigraphique', 'ZPE'];
        $edition_types = ['corpus', 'journal', 'book'];

        for($i = 1; $i <= 1000; $i++) {
            Filing::find($i)->editions()->create([
                'corpus' => $faker->randomElement($corpus),
                'volume' => $faker->boolean(50) ? $faker->randomDigit() : null,
                'number_inscription' => $faker->boolean(80) ? $faker->numberBetween(1, 2000) : null,
                'edition_type' => $faker->randomElement($edition_types),
                'publication_year' => $faker->boolean(20) ? $faker->numberBetween(1800, 2000) : null,
                'corpus_page' => $faker->boolean(50) ? $faker->numberBetween(1, 1000) : null,
                'last_name_author' => $faker->boolean(50) ? $faker->lastName() : null,
                'link' => $faker->url(),
            ]);
        }

        for($i = 1; $i <= 500; $i++) {
            Proposal::find($i)->editions()->create([
                'corpus' => $faker->randomElement($corpus),
                'volume' => $faker->boolean(50) ? $faker->randomDigit() : null,
                'number_inscription' => $faker->boolean(80) ? $faker->numberBetween(1, 2000) : null,
                'publication_year' => $faker->boolean(20) ? $faker->numberBetween(1800, 2000) : null,
                'corpus_page' => $faker->boolean(50) ? $faker->numberBetween(1, 1000) : null,
                'last_name_author' => $faker->boolean(50) ? $faker->lastName() : null,
                'link' => $faker->url(),
            ]);
        }
    }
}
