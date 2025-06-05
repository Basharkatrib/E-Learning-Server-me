<?php

namespace App\Filament\Resources\CourseFaqResource\Pages;

use App\Filament\Resources\CourseFaqResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourseFaqs extends ListRecords
{
    protected static string $resource = CourseFaqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
