<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "profile_image",
        'certificate_url',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
        ];
    }

    public function courses() // As student
    {
        return $this->belongsToMany(Course::class, "course_user")
            ->withPivot("enrolled_at")
            ->withTimestamps();
    }

    public function createdCourses() // As teacher
    {
        return $this->hasMany(Course::class, "created_by");
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, "course_user")
            ->withPivot([
                "enrolled_at",
                "progress",
                "videos_completed",
                "completed_at"
            ])
            ->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === "teacher";
    }

    public function isStudent()
    {
        return $this->role === "student";
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function savedCourses()
    {
        return $this->belongsToMany(Course::class, 'saved_courses')
                    ->withTimestamps();
    }

    public function watchedVideos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'video_user')
            ->withTimestamps()
            ->withPivot('watched_at');
    }


    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
