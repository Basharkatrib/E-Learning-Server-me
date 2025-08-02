<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizAttemptResource\Pages;
use App\Models\QuizAttempt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuizAttemptResource extends Resource
{
    protected static ?string $model = QuizAttempt::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Quiz Management';
    
    protected static ?string $navigationLabel = 'Student Results';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.first_name')
                    ->label('Student Name')
                    ->formatStateUsing(fn ($state, $record) => $record->user->first_name . ' ' . $record->user->last_name)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quiz.course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quiz.title')
                    ->label('Quiz')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('score')
                    ->label('Score')
                    ->formatStateUsing(fn (string $state): string => number_format($state, 0) . '%')
                    ->sortable()
                    ->color(fn (string $state): string => $state >= 70 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('started_at')
                    ->label('Started')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('quiz.course', 'title')
                    ->label('Filter by Course')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('score')
                    ->form([
                        Forms\Components\TextInput::make('score_from')
                            ->label('Minimum Score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                        Forms\Components\TextInput::make('score_to')
                            ->label('Maximum Score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['score_from'],
                                fn (Builder $query, $score): Builder => $query->where('score', '>=', $score),
                            )
                            ->when(
                                $data['score_to'],
                                fn (Builder $query, $score): Builder => $query->where('score', '<=', $score),
                            );
                    }),

                Tables\Filters\Filter::make('completed')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('completed_at'))
                    ->label('Completed Attempts Only'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form([
                        Forms\Components\TextInput::make('user.name')
                            ->label('Student Name')
                            ->disabled(),
                        Forms\Components\TextInput::make('quiz.course.title')
                            ->label('Course')
                            ->disabled(),
                        Forms\Components\TextInput::make('quiz.title')
                            ->label('Quiz')
                            ->disabled(),
                        Forms\Components\TextInput::make('score')
                            ->label('Score')
                            ->suffix('%')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('started_at')
                            ->label('Started At')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Completed At')
                            ->disabled(),
                    ]),
            ])
            ->bulkActions([])
            ->poll('30s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizAttempts::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', today())->count() . ' today';
    }
} 