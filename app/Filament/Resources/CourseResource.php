<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Traits\HasRoleBasedAccess;

class CourseResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Translations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('English')
                            ->schema([
                                Forms\Components\TextInput::make('title.en')
                                    ->label('Title (English)')
                                    ->required(),
                                Forms\Components\Textarea::make('description.en')
                                    ->label('Description (English)')
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Arabic')
                            ->schema([
                                Forms\Components\TextInput::make('title.ar')
                                    ->label('Title (Arabic)')
                                    ->required(),
                                Forms\Components\Textarea::make('description.ar')
                                    ->label('Description (Arabic)')
                                    ->required(),
                            ]),
                    ]),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('duration')
                    ->label('Duration')
                    ->suffix(' minutes')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('difficulty_level')
                    ->label('Difficulty Level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('thumbnail_url')
                    ->label('Image')
                    ->image()
                    ->disk('cloudinary')
                    ->visibility('public')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->maxSize(5120) // 5MB
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                    ->required()
                    ->columnSpanFull()
                    ->preserveFilenames()
                    ->directory('courses'),
                Forms\Components\Select::make('default_language')
                    ->options([
                        'en' => 'English',
                        'ar' => 'Arabic',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return $state[app()->getLocale()] ?? $state['en'] ?? '';
                        }
                        return $state;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return $state[app()->getLocale()] ?? $state['en'] ?? '';
                        }
                        return $state;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->suffix(' minutes')
                    ->sortable(),
                Tables\Columns\TextColumn::make('difficulty_level')
                    ->label('Difficulty Level')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('Image')
                    ->square()
                    ->disk('cloudinary')
                    ->visibility('public')
                    ->columnSpanFull(),
                Tables\Columns\TextColumn::make('default_language')
                    ->label('Default Language')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'ar' => 'Arabic',
                        'en' => 'English',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('difficulty_level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
