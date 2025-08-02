<?php

namespace App\Filament\Resources\BenefitsCourseResource\Pages;

use App\Filament\Resources\BenefitsCourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBenefitsCourses extends ListRecords
{
    protected static string $resource = BenefitsCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 