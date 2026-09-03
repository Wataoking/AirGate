<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'duration', 'price', 'speed', 'active'];

    protected function casts(): array
    {
        return ['price' => 'integer', 'speed' => 'float', 'active' => 'boolean'];
    }
}