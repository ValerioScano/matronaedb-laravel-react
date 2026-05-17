<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;
use Faker\Generator as Faker;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        $category= ['vocabulary', 'role', 'agency'];

        for($i=0; $i<30; $i++) {
            $newTag= new Tag();
            $newTag->name=$faker->word();
            $newTag->label=$faker->word();
            $newTag->category=$faker->randomElement($category);
            $newTag->save();
    }
}
}