<?php

namespace App\Filament\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

trait HasRoleBasedAccess
{
    protected static function isAllowedForRole(string $role): bool
    {
        // Admin can access everything
        if ($role === 'admin') {
            return true;
        }

        // Employee: only Orders
        if ($role === 'employee') {
            return in_array(static::class, [
                \App\Filament\Resources\OrderResource::class,
            ], true);
        }

        // Teacher: whitelist of resources
        if ($role === 'teacher') {
            return in_array(static::class, [
                \App\Filament\Resources\CourseResource::class,
                \App\Filament\Resources\CategoryResource::class,
                \App\Filament\Resources\RatingResource::class,
                \App\Filament\Resources\CourseFaqResource::class,
                \App\Filament\Resources\SectionResource::class,
                \App\Filament\Resources\SkillResource::class,
                \App\Filament\Resources\VideoResource::class,
                \App\Filament\Resources\QuizResource::class,
                \App\Filament\Resources\BenefitsCourseResource::class,
            ], true);
        }

        return false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        $isAllowed = static::isAllowedForRole($user->role);

        Log::info('Navigation access check', [
            'resource' => static::class,
            'user_role' => $user->role,
            'is_allowed' => $isAllowed,
        ]);

        return $isAllowed;
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
            
            // For quizzes, only show quizzes for their courses
            if (static::class === \App\Filament\Resources\QuizResource::class) {
                return $query->whereHas('course', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            }
        }

        return $query;
    }

    // Hard-restrict page access as well (not just navigation)
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user ? static::isAllowedForRole($user->role) : false;
    }

    public static function canView($record): bool
    {
        $user = auth()->user();
        return $user ? static::isAllowedForRole($user->role) : false;
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        return $user ? static::isAllowedForRole($user->role) : false;
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        return $user ? static::isAllowedForRole($user->role) : false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        return $user ? static::isAllowedForRole($user->role) : false;
    }

    public static function canDeleteAny(): bool
    {
        $user = auth()->user();
        return $user ? static::isAllowedForRole($user->role) : false;
    }
} 