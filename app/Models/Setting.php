<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['wifi_ssid', 'wifi_password', 'bandwidth_limit', 'radius_url', 'radius_password', 'radius_secret'];

    protected function casts(): array
    {
        return [
            'wifi_password' => 'encrypted',
            'radius_password' => 'encrypted',
            'radius_secret' => 'encrypted',
            'bandwidth_limit' => 'integer',
        ];
    }
}