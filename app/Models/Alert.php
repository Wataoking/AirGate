<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['type', 'kind', 'title', 'message', 'read_at', 'modem_id', 'user_id'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    public function modem()
    {
        return $this->belongsTo(Modem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}