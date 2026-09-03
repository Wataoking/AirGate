<?php

namespace Database\Seeders;

use App\Models\Modem;
use Illuminate\Database\Seeder;

class ModemSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Modem-Bureau', 'model' => 'AirGate Pro', 'mac_address' => 'A4:6D:3B:9B:12:7F', 'ip_address' => '192.168.1.102', 'data_used' => 12.4, 'bandwidth' => 45],
            ['name' => 'Modem-Étage 1', 'model' => 'AirGate Lite', 'mac_address' => '3C:BB:AA:44:DE:90', 'ip_address' => '192.168.1.115', 'data_used' => 5.1, 'bandwidth' => 12],
            ['name' => 'Modem-Accueil', 'model' => 'AirGate Mini', 'mac_address' => '8F:11:C2:27:AA:33', 'ip_address' => '192.168.1.123', 'data_used' => 3.2, 'bandwidth' => 8],
        ] as $modem) {
            Modem::updateOrCreate(['mac_address' => $modem['mac_address']], $modem);
        }
    }
}