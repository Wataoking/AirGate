<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modem extends Model
{
    protected $fillable = [
        'name', 'model', 'mac_address', 'ip_address', 'data_used', 'bandwidth', 'status',
    ];

    protected function casts(): array
    {
        return [
            'data_used' => 'float',
            'bandwidth' => 'float',
        ];
    }
}