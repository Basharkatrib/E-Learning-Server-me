<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\User;
use App\Models\Rating;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $query = Course::query();

        if ($user->role === 'teacher') {
            $query->where('user_id', $user->id);
        }

        return [
            Stat::make('Total Courses', $query->count())
                ->description('Available Courses')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('success'),

            Stat::make('Total Students', User::where('role', 'student')->count())
                ->description('Registered Students')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Average Rating', number_format(Rating::avg('rating'), 1))
                ->description('Course Ratings Average')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
} 