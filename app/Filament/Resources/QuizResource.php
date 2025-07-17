<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationGroup = 'Course Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Knowledge Check: Course Title'),
                    
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(1000)
                    ->default('Test your understanding of the course material')
                    ->columnSpanFull(),
                    
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('time_limit')
                            ->label('Time Limit (minutes)')
                            ->numeric()
                            ->required()
                            ->default(15)
                            ->minValue(1)
                            ->maxValue(180),
                            
                        Forms\Components\TextInput::make('passing_score')
                            ->label('Passing Score (%)')
                            ->numeric()
                            ->required()
                            ->default(70)
                            ->minValue(0)
                            ->maxValue(100),
                    ]),
                    
                Forms\Components\Toggle::make('is_published')
                    ->label('Published')
                    ->default(true)
                    ->helperText('Only published quizzes are visible to students'),
                    
                Forms\Components\Section::make('Questions')
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('question_text')
                                    ->required()
                                    ->maxLength(1000)
                                    ->columnSpanFull(),
                                    
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('question_type')
                                            ->options([
                                                'multiple_choice' => 'Multiple Choice',
                                                'true_false' => 'True/False'
                                            ])
                                            ->required()
                                            ->default('multiple_choice')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                if ($state === 'true_false') {
                                                    $set('options', [
                                                        ['option_text' => 'True', 'is_correct' => true],
                                                        ['option_text' => 'False', 'is_correct' => false]
                                                    ]);
                                                }
                                            }),
                                            
                                        Forms\Components\TextInput::make('points')
                                            ->numeric()
                                            ->required()
                                            ->default(1)
                                            ->minValue(1)
                                            ->maxValue(100),
                                    ]),
                                    
                                Forms\Components\Repeater::make('options')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('option_text')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Option'),
                                            
                                        Forms\Components\Toggle::make('is_correct')
                                            ->required()
                                            ->label('Correct Answer')
                                            ->afterStateUpdated(function ($state, Forms\Set $set, $context) {
                                                // If this option is marked as correct, set all other options to incorrect
                                                if ($state && $context === 'create') {
                                                    $set('../options.*.is_correct', false);
                                                    $set('is_correct', true);
                                                }
                                            }),
                                    ])
                                    ->defaultItems(function (callable $get) {
                                        $type = $get('question_type');
                                        return $type === 'true_false' ? 2 : 4;
                                    })
                                    ->minItems(function (callable $get) {
                                        $type = $get('question_type');
                                        return $type === 'true_false' ? 2 : 2;
                                    })
                                    ->maxItems(function (callable $get) {
                                        $type = $get('question_type');
                                        return $type === 'true_false' ? 2 : 6;
                                    })
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(1)
                            ->minItems(1)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? null),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label('Questions'),
                    
                Tables\Columns\TextColumn::make('time_limit')
                    ->label('Duration')
                    ->suffix(' min')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('passing_score')
                    ->suffix('%')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published Status')
                    ->placeholder('All Quizzes')
                    ->trueLabel('Published Quizzes')
                    ->falseLabel('Unpublished Quizzes'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
} 