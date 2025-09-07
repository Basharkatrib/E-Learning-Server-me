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
        $user = auth()->user();
        
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Student Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('first_name')
                            ->label('First Name'),
                        Infolists\Components\TextEntry::make('last_name')
                            ->label('Last Name'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email Address'),
                        Infolists\Components\TextEntry::make('phone_number')
                            ->label('Phone Number'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Registration Date')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Enrolled Courses')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('enrolledCourses')
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                                    ->label('Course Title'),
                                Infolists\Components\TextEntry::make('pivot.progress')
                                    ->label('Progress')
                                    ->formatStateUsing(fn ($state) => $state . '%'),
                                Infolists\Components\TextEntry::make('pivot.enrolled_at')
                                    ->label('Enrollment Date')
                                    ->dateTime(),
                                Infolists\Components\TextEntry::make('pivot.status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'active' => 'success',
                                        'completed' => 'success',
                                        'pending' => 'warning',
                                        'cancelled' => 'danger',
                                        default => 'gray',
                                    }),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }
} 