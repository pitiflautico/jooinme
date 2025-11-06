<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcription extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_slot_id',
        'conversation_id',
        'content',
        'summary',
        'key_points',
        'participants',
        'language',
        'status',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'key_points' => 'array',
            'participants' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function scheduleSlot()
    {
        return $this->belongsTo(ScheduleSlot::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);
    }
}
