<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentEnrollmentResource\Pages;
use App\Filament\Traits\HasRoleBasedAccess;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class StudentEnrollmentResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'Student Enrollments';
    
    protected static ?string $navigationGroup = 'Education Management';
    
    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Student Name')
                    ->formatStateUsing(fn ($state, $record) => $record->first_name . ' ' . $record->last_name)
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
                    ->label('Filter by Course')
                    ->modifyQueryUsing(function (Builder $query, array $data) {
                        $user = auth()->user();
                        if ($user && $user->role === 'teacher') {
                            // For teachers, only show courses they created
                            $query->whereHas('enrolledCourses', function ($query) use ($user) {
                                $query->where('courses.user_id', $user->id);
                            });
                        }
                        return $query;
                    }),
                    
                Tables\Filters\SelectFilter::make('enrollment_status')
                    ->label('Enrollment Status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'cancelled' => 'Cancelled',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['value']) {
                            return $query->whereHas('enrolledCourses', function ($query) use ($data) {
                                $query->where('course_user.status', $data['value']);
                            });
                        }
                        return $query;
                    }),
                    
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
                $user = auth()->user();
                
                // Base query with enrolled courses relationship
                $query = $query->with(['enrolledCourses' => function ($query) use ($user) {
                    $query->select('courses.id', 'courses.title', 'course_user.progress', 'courses.user_id');
                    
                    // For teachers, only load their own courses
                    if ($user && $user->role === 'teacher') {
                        $query->where('courses.user_id', $user->id);
                    }
                }])->whereHas('enrolledCourses');
                
                // For teachers, only show students enrolled in their courses
                if ($user && $user->role === 'teacher') {
                    $query->whereHas('enrolledCourses', function ($query) use ($user) {
                        $query->where('courses.user_id', $user->id);
                    });
                }
                
                return $query;
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