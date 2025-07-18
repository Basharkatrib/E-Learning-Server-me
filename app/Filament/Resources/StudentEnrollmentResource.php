<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentEnrollmentResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class StudentEnrollmentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'Student Enrollments';
    
    protected static ?string $navigationGroup = 'Education Management';
    
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('enrolledCourses.title')
                    ->label('Enrolled Courses')
                    ->listWithLineBreaks()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('enrolledCourses.pivot.progress')
                    ->label('Course Progress')
                    ->state(function (User $record): string {
                        return $record->enrolledCourses->map(function ($course) {
                            return $course->pivot->progress . '%';
                        })->join(', ');
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enrollment Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('courses')
                    ->relationship('enrolledCourses', 'title')
                    ->multiple()
                    ->label('Filter by Course'),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('enrolled_from')
                            ->label('Enrolled From'),
                        Forms\Components\DatePicker::make('enrolled_until')
                            ->label('Enrolled Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['enrolled_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['enrolled_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                return $query->with(['enrolledCourses' => function ($query) {
                    $query->select('courses.id', 'courses.title', 'course_user.progress');
                }])->whereHas('enrolledCourses');
            });
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentEnrollments::route('/'),
            'view' => Pages\ViewStudentEnrollment::route('/{record}'),
        ];
    }
} 