<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'scheduled_at',
        'ends_at',
        'status',
        'confirmed_participants',
        'attended_participants',
        'notes',
        'recording_url',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'ends_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function start()
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'ended_at' => now(),
        ]);
    }

    public function isPast(): bool
    {
        return $this->scheduled_at < now();
    }

    public function isComing(): bool
    {
        return $this->scheduled_at->isFuture();
    }
}
