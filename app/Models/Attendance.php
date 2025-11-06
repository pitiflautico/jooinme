<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_slot_id',
        'conversation_id',
        'status',
        'confirmed_at',
        'checked_in_at',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'confirmed_at' => 'datetime',
            'checked_in_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scheduleSlot()
    {
        return $this->belongsTo(ScheduleSlot::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function markAsAttended()
    {
        $this->update([
            'status' => 'attended',
            'checked_in_at' => now(),
        ]);
    }
}
