<?php

namespace App\Filament\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

trait HasRoleBasedAccess
{
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Admin can see everything
        if ($user->role == 'admin') {
            return true;
        }

        // Teacher can see these resources
        if ($user->role === 'teacher') {
            $allowedResources = [
                \App\Filament\Resources\CourseResource::class,
                \App\Filament\Resources\CategoryResource::class,
                \App\Filament\Resources\RatingResource::class,
                \App\Filament\Resources\CourseFaqResource::class,
                \App\Filament\Resources\SectionResource::class,
                \App\Filament\Resources\SkillResource::class,
                \App\Filament\Resources\VideoResource::class,
                // Add any other resources you want teachers to see
            ];

            $isAllowed = in_array(static::class, $allowedResources);
            
            // Log for debugging
            Log::info('Resource access check', [
                'resource' => static::class,
                'user_role' => $user->role,
                'is_allowed' => $isAllowed
            ]);

            return $isAllowed;
        }

        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->role === 'teacher') {
            // For teachers, only show their own courses
            if (static::class === \App\Filament\Resources\CourseResource::class) {
                return $query->where('user_id', $user->id);
            }
            
            // For ratings, only show ratings for their courses
            if (static::class === \App\Filament\Resources\RatingResource::class) {
                return $query->whereHas('course', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            }
        }

        return $query;
    }
} 