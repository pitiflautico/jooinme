<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'interests',
        'availability',
        'preferences',
        'location',
        'latitude',
        'longitude',
        'timezone',
        'is_verified',
        'is_active',
        'last_active_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'interests' => 'array',
            'availability' => 'array',
            'preferences' => 'array',
            'is_verified' => 'boolean',
            'is_active' => 'boolean',
            'last_active_at' => 'datetime',
        ];
    }

    // Relationships

    /**
     * Conversations owned by this user
     */
    public function ownedConversations()
    {
        return $this->hasMany(Conversation::class, 'owner_id');
    }

    /**
     * Conversations this user participates in
     */
    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    /**
     * Conversations this user is a part of (through participations)
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'participations')
            ->withPivot('status', 'role')
            ->withTimestamps();
    }

    /**
     * Feedback given by this user
     */
    public function feedbackGiven()
    {
        return $this->hasMany(Feedback::class, 'user_id');
    }

    /**
     * Feedback received by this user
     */
    public function feedbackReceived()
    {
        return $this->hasMany(Feedback::class, 'rated_user_id');
    }

    /**
     * Reports filed by this user
     */
    public function reportsFiled()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Reports against this user
     */
    public function reportsReceived()
    {
        return $this->hasMany(Report::class, 'reported_user_id');
    }

    /**
     * Users this user is blocking
     */
    public function blocking()
    {
        return $this->belongsToMany(User::class, 'blocks', 'blocker_id', 'blocked_id')
            ->withTimestamps();
    }

    /**
     * Users blocking this user
     */
    public function blockedBy()
    {
        return $this->belongsToMany(User::class, 'blocks', 'blocked_id', 'blocker_id')
            ->withTimestamps();
    }

    /**
     * Users this user is following
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    /**
     * Users following this user
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    /**
     * Attendances for this user
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Helper Methods

    /**
     * Check if user is blocking another user
     */
    public function isBlocking(User $user): bool
    {
        return $this->blocking()->where('blocked_id', $user->id)->exists();
    }

    /**
     * Check if user is following another user
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    /**
     * Get average rating from feedback
     */
    public function averageRating(): float
    {
        return $this->feedbackReceived()->avg('rating') ?? 0;
    }

    /**
     * Get total conversations attended
     */
    public function totalAttendances(): int
    {
        return $this->attendances()->where('status', 'attended')->count();
    }

    /**
     * Mark user as online
     */
    public function markAsOnline(): void
    {
        $this->update(['last_active_at' => now()]);
    }
}
