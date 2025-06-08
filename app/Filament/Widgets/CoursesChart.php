<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CoursesChart extends ChartWidget
{
    protected static ?string $heading = 'Available Courses';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $user = auth()->user();
        $query = Course::query();

        if ($user->role === 'teacher') {
            $query->where('user_id', $user->id);
        }

        $data = $query->select('difficulty_level', DB::raw('count(*) as total'))
            ->groupBy('difficulty_level')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Number of Courses',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#10B981',
                        '#F59E0B',
                        '#EF4444',
                    ],
                ],
            ],
            'labels' => $data->pluck('difficulty_level')->map(function ($level) {
                return match($level) {
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                    default => $level,
                };
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
} 