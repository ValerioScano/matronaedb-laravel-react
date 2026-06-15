<?php

namespace Database\Seeders;

use App\Models\Filing;
use App\Models\Proposal;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class PeopleSeeder extends Seeder
{
    public function run(Faker $faker): void
    {
        $praenomina = ['Marcus', 'Gaius', 'Lucius', 'Quintus', 'Publius', 'Titus', 'Gnaeus', 'Aulus', 'Manius', 'Spurius', 'Julia', 'Claudia', 'Valeria', 'Flavia', 'Aurelia'];
        $nomina     = ['Iulius', 'Claudius', 'Valerius', 'Cornelius', 'Aemilius', 'Pompeius', 'Sempronius', 'Tullius', 'Fabius', 'Licinius', 'Iulia', 'Claudia', 'Valeria', 'Cornelia', 'Aemilia'];
        $cognomina  = ['Maximus', 'Rufus', 'Niger', 'Longus', 'Felix', 'Fortunatus', 'Sabinus', 'Primus', 'Secundus', 'Tertius', 'Maior', 'Minor', 'Verus', 'Clemens', 'Pius'];

        $filingIds = Filing::pluck('id');

        foreach ($filingIds as $filingId) {
            $filing = Filing::find($filingId);
            $count = $faker->numberBetween(0, 3);
            for ($i = 0; $i < $count; $i++) {
                $filing->people()->create([
                    'praenomen' => $faker->boolean(80) ? $faker->randomElement($praenomina) : null,
                    'nomen'     => $faker->randomElement($nomina),
                    'cognomen'  => $faker->boolean(60) ? $faker->randomElement($cognomina) : null,
                    'TM_PER_id' => $faker->boolean(50) ? $faker->numberBetween(1000, 99999) : null,
                ]);
            }
        }

        $proposalIds = Proposal::pluck('id');

        foreach ($proposalIds as $proposalId) {
            $proposal = Proposal::find($proposalId);
            $count = $faker->numberBetween(0, 3);
            for ($i = 0; $i < $count; $i++) {
                $proposal->people()->create([
                    'praenomen' => $faker->boolean(80) ? $faker->randomElement($praenomina) : null,
                    'nomen'     => $faker->randomElement($nomina),
                    'cognomen'  => $faker->boolean(60) ? $faker->randomElement($cognomina) : null,
                    'TM_PER_id' => $faker->boolean(50) ? $faker->numberBetween(1000, 99999) : null,
                ]);
            }
        }
    }
}
