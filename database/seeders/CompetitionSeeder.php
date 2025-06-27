<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Competition;
use Illuminate\Support\Str;


class CompetitionSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            Competition::create([
                'name' => 'Testvõistlus ' . Str::random(5),
                'description' => fake()->sentence(3, true),
                'dt_start' => now()->addDays(rand(1, 10)),
                'dt_end' => now()->addDays(rand(11, 20)),
                'attempt_count' => rand(0, 5),
                'game_data' => json_encode(["mis"=>"liitmine,lahutamine,korrujagamine", "level"=>"125", "tyyp"=>"integer", "aeg"=>rand(1,10)]),
            ]);
        }
    }
}
