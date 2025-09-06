<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Enrollment;
use App\Filament\Traits\HasRoleBasedAccess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $navigationGroup = 'Payments';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('course_id')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('price_paid')
                    ->numeric()
                    ->prefix('SYP')
                    ->nullable(),
                Forms\Components\Select::make('payment_method')
                    ->options([
                        'syriatel' => 'Syriatel Cash',
                        'mtn' => 'MTN Cash',
                    ])
                    ->nullable(),
                Forms\Components\TextInput::make('transation_id')
                    ->label('Transaction ID')
                    ->nullable(),
                Forms\Components\TextInput::make('payment_screenshot_path')
                    ->label('Screenshot Path')
                    ->nullable(),
                Forms\Components\DateTimePicker::make('enrolled_at')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('user.first_name')
                    ->label('Student Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_paid')
                    ->money('SYP', true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'syriatel' => 'Syriatel',
                        'mtn' => 'MTN',
                        default => '—',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('accept')
                    ->visible(fn (Enrollment $record) => $record->status !== 'accepted')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Enrollment $record) => $record->update(['status' => 'accepted'])),
                Tables\Actions\Action::make('reject')
                    ->visible(fn (Enrollment $record) => $record->status !== 'rejected')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Enrollment $record) => $record->update(['status' => 'rejected'])),
                Tables\Actions\Action::make('pending')
                    ->visible(fn (Enrollment $record) => $record->status !== 'pending')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(fn (Enrollment $record) => $record->update(['status' => 'pending'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}


