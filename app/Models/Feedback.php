<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_slot_id',
        'user_id',
        'conversation_id',
        'rated_user_id',
        'rating',
        'content_rating',
        'organization_rating',
        'atmosphere_rating',
        'comment',
        'testimonial',
        'is_public',
        'attended',
        'would_recommend',
        'tags',
    ];

    protected function casts(): array
    {
        return [
            'attended' => 'boolean',
            'would_recommend' => 'boolean',
            'is_public' => 'boolean',
            'tags' => 'array',
        ];
    }

    public function scheduleSlot()
    {
        return $this->belongsTo(ScheduleSlot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function ratedUser()
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }
}
