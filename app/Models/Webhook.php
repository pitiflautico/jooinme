<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'url',
        'secret',
        'events',
        'is_active',
        'retry_count',
        'last_called_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'is_active' => 'boolean',
            'last_called_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function call(array $payload): bool
    {
        // TODO: Implement webhook calling logic
        $this->update(['last_called_at' => now()]);
        return true;
    }

    public function disable()
    {
        $this->update([
            'is_active' => false,
            'status' => 'disabled',
        ]);
    }
}
