<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasTranslations;

    protected $fillable = [
        "title",
        "description",
        "category_id",
        "created_by",
        "difficulty_level",
        "default_language",
        "thumbnail_url",
        "duration"
    ];

    public $translatable = ['title', 'description'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'course_skill');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(CourseFAQ::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot("enrolled_at")
            ->withTimestamps();
    }
}
