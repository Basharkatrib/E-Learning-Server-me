<?php

namespace App\Filament\Widgets;

use App\Models\Rating;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RatingsChart extends ChartWidget
{
    protected static ?string $heading = 'Ratings Distribution';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $user = auth()->user();
        $query = Rating::query();

        if ($user->role === 'teacher') {
            $query->whereHas('course', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $data = $query->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Number of Ratings',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => '#3B82F6',
                ],
            ],
            'labels' => $data->pluck('rating')->map(fn($rating) => "{$rating} Stars")->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
} 