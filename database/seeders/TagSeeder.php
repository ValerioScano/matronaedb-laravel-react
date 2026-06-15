<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Faker\Generator as Faker;

class TagSeeder extends Seeder
{
    public function run(Faker $faker): void
    {
        $categories = ['vocabulary', 'role', 'agency'];

        for ($i = 0; $i < 30; $i++) {
            $name     = $faker->word();
            $label    = $faker->word();
            $category = $faker->randomElement($categories);

            Tag::create([
                'name'     => $name,
                'label'    => $label,
                'category' => $category,
            ]);

            Tag::create([
                'name'     => $name . '?',
                'label'    => $label . '?',
                'category' => $category,
            ]);
        }
    }
}
