<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasTranslations;

    protected $fillable = [
        "title",
        "description",
        "category_id",
        "user_id",
        "difficulty_level",
        "default_language",
        "thumbnail_url",
        "price",
        "duration",
        "link",
        "document_url",
        "is_sequential",
    ];

    protected $casts = [
        'document_url' => 'string',
    ];

    public $translatable = ["description", "title", "duration"];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
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

    public function calculateTotalDuration()
    {
        return $this->sections->sum(function ($section) {
            return $section->calculateDuration();
        });
    }

    /**
     * Get the benefits for the course.
     */
    public function benefits()
    {
        return $this->hasMany(BenefitsCourse::class)->orderBy('order');
    }

    public function quiz():HasOne {
        return $this->hasOne(Quiz::class);
    }

    /**
     * Get the full URL for the thumbnail
     */
    public function getThumbnailUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        // If it's already a full URL, return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        // Convert relative path to full URL
        return config('app.url') . '/storage/' . $value;
    }

    /**
     * Set the thumbnail URL - convert to full URL when saving
     */
    public function setThumbnailUrlAttribute($value)
    {
        // Handle array from FileUpload component
        if (is_array($value)) {
            $value = $value[0] ?? null;
        }
        
        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
            // Convert relative path to full URL
            $this->attributes['thumbnail_url'] = config('app.url') . '/storage/' . $value;
        } else {
            $this->attributes['thumbnail_url'] = $value;
        }
    }
}
