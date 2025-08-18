<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BenefitsCourseResource\Pages;
use App\Models\BenefitsCourse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Traits\HasRoleBasedAccess;

class BenefitsCourseResource extends Resource
{
    use HasRoleBasedAccess;
    
    protected static ?string $model = BenefitsCourse::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Course Management';

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
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Tabs\Tab::make('Arabic')
                            ->schema([
                                Forms\Components\TextInput::make('title.ar')
                                    ->label('Title (Arabic)')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'title', function (\Illuminate\Database\Eloquent\Builder $query) {
                        // If user is a teacher, only show their courses
                        if (auth()->user()->role === 'teacher') {
                            $query->where('user_id', auth()->id());
                        }
                        return $query;
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Course'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (\Illuminate\Database\Eloquent\Builder $query) {
                // If user is a teacher, only show benefits from their courses
                if (auth()->user()->role === 'teacher') {
                    $query->whereHas('course', function (\Illuminate\Database\Eloquent\Builder $courseQuery) {
                        $courseQuery->where('user_id', auth()->id());
                    });
                }
                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return $state[app()->getLocale()] ?? $state['en'] ?? '';
                        }
                        return $state;
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return $state[app()->getLocale()] ?? $state['en'] ?? '';
                        }
                        return $state;
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('course', 'title', function (\Illuminate\Database\Eloquent\Builder $query) {
                        // If user is a teacher, only show their courses
                        if (auth()->user()->role === 'teacher') {
                            $query->where('user_id', auth()->id());
                        }
                        return $query;
                    })
                    ->searchable()
                    ->preload()
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
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBenefitsCourses::route('/'),
            'create' => Pages\CreateBenefitsCourse::route('/create'),
            'edit' => Pages\EditBenefitsCourse::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Course Benefits';
    }

    public static function getModelLabel(): string
    {
        return 'Course Benefit';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Course Benefits';
    }
}
