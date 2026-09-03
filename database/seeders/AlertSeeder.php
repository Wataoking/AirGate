<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Modem;
use Illuminate\Database\Seeder;

class AlertSeeder extends Seeder
{
    public function run(): void
    {
        $modem = Modem::first();

        foreach ([
            ['type' => 'alert', 'title' => 'Usage réseau à surveiller', 'message' => 'La consommation du réseau nécessite une vérification.', 'modem_id' => $modem?->id],
            ['type' => 'info', 'title' => 'Réseau opérationnel', 'message' => 'Les services AirGate sont disponibles.', 'modem_id' => null],
        ] as $index => $alert) {
            Alert::updateOrCreate(['title' => $alert['title']], $alert + ['read_at' => $index === 1 ? now() : null]);
        }
    }
}