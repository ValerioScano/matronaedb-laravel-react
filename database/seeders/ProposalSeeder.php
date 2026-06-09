<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proposal;
use Faker\Generator as Faker;

class ProposalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for($i=0; $i<500; $i++) {
            $year1 = $faker->numberBetween(-100, 700);
            $year2 = $faker->numberBetween(-100, 700);
            $newProposal= new Proposal();
            $newProposal->text = $faker->paragraphs(4, true);
            $newProposal->region = $faker->country();
            $newProposal->province= $faker->state();
            $newProposal->city = $faker->city();
            $newProposal->min_year = min($year1, $year2);
            $newProposal->max_year = max($year1, $year2);
            $newProposal->is_certain_date = $faker->boolean(50);
            $newProposal->is_sacred_dedication = $faker->boolean(50);
            $newProposal->notes = $faker->boolean(25) ? $faker->paragraphs(4, true) : null;
            $newProposal->religion= $faker->randomElement(['pagan', 'uncertain', 'christian']);
            $newProposal->status = $faker->randomElement(['pending', 'approved', 'rejected']);
            $newProposal->rejection_notes = $newProposal->status === 'rejected' ? $faker->paragraphs(2, true) : null;
            $newProposal->private_notes = $faker->sentence();
            $newProposal->proposed_by = $faker->numberBetween(1, 100);
            $newProposal->approved_by = $newProposal->status === 'pending' ? null : $faker->numberBetween(1, 100);
            $newProposal->filing_id = $faker->boolean(50) ? $faker->numberBetween(1, 150) : null;
            $newProposal->save();
        }
    }
}
