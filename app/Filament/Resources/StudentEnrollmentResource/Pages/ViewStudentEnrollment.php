<?php

namespace App\Filament\Resources\StudentEnrollmentResource\Pages;

use App\Filament\Resources\StudentEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewStudentEnrollment extends ViewRecord
{
    protected static string $resource = StudentEnrollmentResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Student Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Student Name'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email Address'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Registration Date')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Enrolled Courses')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('courses')
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                                    ->label('Course Title'),
                                Infolists\Components\TextEntry::make('progress')
                                    ->label('Progress')
                                    ->formatStateUsing(fn ($state) => $state . '%'),
                                Infolists\Components\TextEntry::make('pivot.created_at')
                                    ->label('Enrollment Date')
                                    ->dateTime(),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
} 