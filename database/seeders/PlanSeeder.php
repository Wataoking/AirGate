<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'ACCES', 'duration' => '1H', 'price' => 500, 'speed' => 5],
            ['name' => 'ACCESS+', 'duration' => '1 JOUR', 'price' => 2000, 'speed' => 10],
            ['name' => 'EVASION', 'duration' => '7 JOURS', 'price' => 10000, 'speed' => 20],
        ] as $plan) {
            Plan::updateOrCreate(['name' => $plan['name']], $plan);
        }
    }
}