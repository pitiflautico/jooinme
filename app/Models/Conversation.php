<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'topic_id',
        'title',
        'description',
        'slug',
        'frequency',
        'duration_minutes',
        'preferred_time',
        'day_of_week',
        'day_of_month',
        'type',
        'location',
        'location_details',
        'latitude',
        'longitude',
        'privacy',
        'max_participants',
        'min_participants',
        'current_participants',
        'meeting_platform',
        'meeting_url',
        'cover_image',
        'tags',
        'settings',
        'status',
        'is_featured',
        'is_active',
        'allow_chat',
        'allow_recording',
        'auto_approve',
        'require_approval',
        'auto_confirm',
        'first_session_at',
        'last_session_at',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'settings' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'allow_chat' => 'boolean',
            'allow_recording' => 'boolean',
            'auto_approve' => 'boolean',
            'require_approval' => 'boolean',
            'auto_confirm' => 'boolean',
            'first_session_at' => 'datetime',
            'last_session_at' => 'datetime',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($conversation) {
            if (empty($conversation->slug)) {
                $conversation->slug = Str::slug($conversation->title) . '-' . Str::random(6);
            }
        });
    }

    // Relationships

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participations')
            ->withPivot('status', 'role')
            ->withTimestamps();
    }

    public function acceptedParticipants()
    {
        return $this->participants()->wherePivot('status', 'accepted');
    }

    public function scheduleSlots()
    {
        return $this->hasMany(ScheduleSlot::class);
    }

    public function upcomingSlots()
    {
        return $this->scheduleSlots()
            ->where('scheduled_at', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('scheduled_at');
    }

    public function nextSlot()
    {
        return $this->upcomingSlots()->first();
    }

    public function externalLink()
    {
        return $this->hasOne(ExternalLink::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    // Helper Methods

    public function isOwner(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function isMember(User $user): bool
    {
        return $this->participants()->where('users.id', $user->id)->exists();
    }

    public function isPublic(): bool
    {
        return $this->privacy === 'public';
    }

    public function isFull(): bool
    {
        return $this->acceptedParticipants()->count() >= $this->max_participants;
    }

    public function averageRating(): float
    {
        return $this->feedback()->avg('rating') ?? 0;
    }

    public function totalSessions(): int
    {
        return $this->scheduleSlots()->where('status', 'completed')->count();
    }

    public function canJoin(User $user): bool
    {
        if ($this->isOwner($user)) {
            return false;
        }

        if ($this->isMember($user)) {
            return false;
        }

        if ($this->isFull()) {
            return false;
        }

        return true;
    }
}
