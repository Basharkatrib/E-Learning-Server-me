<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Traits\HasRoleBasedAccess;

class CategoryResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Translations')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('English')
                            ->schema([
                                Forms\Components\TextInput::make('name.en')
                                    ->label('Name (English)')
                                    ->required(),
                                Forms\Components\Textarea::make('description.en')
                                    ->label('Description (English)')
                                    ->required(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Arabic')
                            ->schema([
                                Forms\Components\TextInput::make('name.ar')
                                    ->label('Name (Arabic)')
                                    ->required(),
                                Forms\Components\Textarea::make('description.ar')
                                    ->label('Description (Arabic)')
                                    ->required(),
                            ]),
                    ]),
                Forms\Components\Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(function ($record) {
                        return Category::when($record, function ($query) use ($record) {
                            // Exclude current category and its children from parent options
                            return $query->where('id', '!=', $record->id)
                                       ->whereNotIn('id', function ($subquery) use ($record) {
                                           $subquery->select('id')
                                                   ->from('categories')
                                                   ->where('parent_id', $record->id);
                                       });
                        })->pluck('name', 'id');
                    })
                    ->searchable()
                    ->placeholder('Choose Parent Category')
                    ->helperText('Choose the parent category that this subcategory belongs to')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent_id')
                    ->label('Parent Category')
                    ->formatStateUsing(fn ($state) => Category::find($state)?->name ?? 'None')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }    
}
