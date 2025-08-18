<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Filament\Resources\VideoResource\RelationManagers;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use App\Filament\Traits\HasRoleBasedAccess;

class VideoResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Course Management';


    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Videos';

    protected static ?string $modelLabel = 'Video';

    protected static ?string $pluralModelLabel = 'Videos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                Forms\Components\TextInput::make('title.en')
                                    ->label('Title (English)')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Tab::make('Arabic')
                            ->schema([
                                Forms\Components\TextInput::make('title.ar')
                                    ->label('Title (Arabic)')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columnSpanFull(),
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->relationship('section.course', 'title', function (Builder $query) {
                        // If user is a teacher, only show their courses
                        if (auth()->user()->role === 'teacher') {
                            $query->where('user_id', auth()->id());
                        }
                        return $query;
                    })
                    ->searchable()
                    ->preload()
                    ->live(),
                    Forms\Components\TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->nullable()
                    ->maxLength(255),
                Forms\Components\Select::make('section_id')
                    ->label('Section')
                    ->relationship('section', 'title', fn (\Illuminate\Database\Eloquent\Builder $query, Forms\Get $get) => $query->where('course_id', $get('course_id')))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('order')
                            ->label('Order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\Hidden::make('course_id')
                            ->default(fn (Forms\Get $get) => $get('course_id')),
                    ]),
                Forms\Components\Toggle::make('is_preview')
                    ->label('Is Preview')
                    ->default(false),
                Forms\Components\TextInput::make('duration')
                    ->label('Duration (minutes)')
                    ->numeric()
                    ->nullable(),
                Forms\Components\FileUpload::make('thumbnail_url')
                    ->label('Thumbnail Image')
                    ->image()
                    ->directory('video-thumbnails')
                    ->disk('public')
                    ->visibility('public')
                    ->nullable(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // If user is a teacher, only show videos from their courses
                if (auth()->user()->role === 'teacher') {
                    $query->whereHas('section.course', function (Builder $courseQuery) {
                        $courseQuery->where('user_id', auth()->id());
                    });
                }
                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return $state[app()->getLocale()] ?? $state['en'] ?? '';
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('section.title')
                    ->label('Section')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return $state[app()->getLocale()] ?? $state['en'] ?? '';
                        }
                        return $state;
                    }),
                Tables\Columns\IconColumn::make('is_preview')
                    ->label('Preview')
                    ->boolean(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->suffix(' mins')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('video_url')
                    ->label('Vedio Url'),
                Tables\Columns\ImageColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->square()
                    ->disk('public')
                    ->visibility('public'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('section')
                    ->relationship('section', 'title', function (Builder $query) {
                        // If user is a teacher, only show sections from their courses
                        if (auth()->user()->role === 'teacher') {
                            $query->whereHas('course', function (Builder $courseQuery) {
                                $courseQuery->where('user_id', auth()->id());
                            });
                        }
                        return $query;
                    })
                    ->label('Section'),
                Tables\Filters\TernaryFilter::make('is_preview')
                    ->label('Is Preview'),
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}
