<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuizResource\Pages;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Form;
use App\Filament\Traits\HasRoleBasedAccess;

class QuizResource extends Resource
{
    use HasRoleBasedAccess;
    
    protected static ?string $model = Quiz::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationLabel = 'Quizzes';
    
    protected static ?string $navigationGroup = 'Quiz Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Quiz Details')
                    ->schema([
                        Forms\Components\Select::make('course_id')
                            ->relationship('course', 'title', function (Builder $query) {
                                $user = auth()->user();
                                if ($user && $user->role === 'teacher') {
                                    return $query->where('user_id', $user->id);
                                }
                                return $query;
                            })
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000),

                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('time_limit')
                                    ->numeric()
                                    ->required()
                                    ->suffix('minutes')
                                    ->default(30),

                                Forms\Components\TextInput::make('passing_score')
                                    ->numeric()
                                    ->required()
                                    ->suffix('%')
                                    ->default(60),

                                Forms\Components\Toggle::make('is_published')
                                    ->label('Published')
                                    ->default(false),
                            ]),
                    ]),

                Forms\Components\Section::make('Questions')
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('question_text')
                                    ->required()
                                    ->maxLength(1000)
                                    ->label('Question')
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
                                            ->reactive(),

                                        Forms\Components\TextInput::make('points')
                                            ->numeric()
                                            ->required()
                                            ->default(1)
                                            ->minValue(1)
                                            ->maxValue(10),
                                    ]),

                                Forms\Components\Section::make('Answer Options')
                                    ->schema([
                                        Forms\Components\Repeater::make('options')
                                            ->relationship()
                                            ->schema([
                                                Forms\Components\Grid::make(2)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('option_text')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->label('Option'),

                                                        Forms\Components\Toggle::make('is_correct')
                                                            ->label('Correct Answer')
                                                            ->required(),
                                                    ]),
                                            ])
                                            ->minItems(2)
                                            ->maxItems(6)
                                            ->defaultItems(4)
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                            ])
                            ->itemLabel(fn (array $state): ?string => $state['question_text'] ?? null)
                            ->collapsible()
                            ->defaultItems(1)
                            ->minItems(1),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Quiz Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable(),

                Tables\Columns\TextColumn::make('passing_score')
                    ->label('Passing Score')
                    ->suffix('%'),

                Tables\Columns\TextColumn::make('time_limit')
                    ->label('Time Limit')
                    ->suffix(' minutes'),

                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions'),

                Tables\Columns\TextColumn::make('attempts_count')
                    ->label('Total Attempts')
                    ->counts('attempts'),

                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title', function (Builder $query) {
                        $user = auth()->user();
                        if ($user && $user->role === 'teacher') {
                            return $query->where('user_id', $user->id);
                        }
                        return $query;
                    })
                    ->label('Filter by Course'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
} 