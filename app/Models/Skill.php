<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Skill extends Model
{
    use HasTranslations;

    protected $fillable = [
        "name"
    ];

    public $translatable = [
        'name'
    ];

    protected $table = 'skills';

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, "course_skill");
    }
}
