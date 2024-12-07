<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use App\Models\BookingTransaction;
use Filament\Forms;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function getNavigationBadge(): ?string
    {
        return (string) BookingTransaction::where('is_paid', false)->count();
    }

    protected static ?string $navigationGroup = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Buyer Information')
                    ->columns(2)
                    ->description('Information about the buyer.')
                        ->schema([
                Forms\Components\TextInput::make('trx_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                        ]),
                    Wizard\Step::make('Booking Information')
                    ->columns(2)
                    ->description('Place and field information.')
                        ->schema([
                Forms\Components\Select::make('place_id')
                    ->required()
                    ->relationship('place', 'name')
                    ->reactive(),
                Forms\Components\Select::make('field_id')
                    ->required()
                    ->options(function (callable $get) {
                        $placeId = $get('place_id');
                        if ($placeId) {
                            return \App\Models\Field::where('place_id', $placeId)->pluck('name', 'id');
                        }
                        return [];
                    })
                    ->reactive(),
                Forms\Components\DatePicker::make('booking_date')
                    ->required(),
                Forms\Components\TimePicker::make('booking_time')
                    ->required(),
                Forms\Components\TextInput::make('total_sesi')
                    ->required()
                    ->numeric()
                    ->default(1),
                        ]),
                    Wizard\Step::make('Payment Information')
                    ->columns(2)
                    ->description('Customer payment information')
                        ->schema([
                Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->prefix('IDR')
                    ->numeric(),
                Forms\Components\Toggle::make('is_paid')
                    ->required(),
                Forms\Components\FileUpload::make('proof')
                    ->required()
                    ->image()
                    ->disk('public')
                    ->directory('proofs'),
                    ]),
                ])
                ->columnSpan('full')
                ->skippable()
                ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trx_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->description(fn ($record) => $record->email),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->prefix('RP. ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('place.name')
                    ->label('Place')
                    ->sortable()
                    ->searchable()
                    ->description(fn ($record) => $record->field->name),
                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Booking Date')
                    ->date('d M Y')
                    ->sortable()
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
                ActionGroup::make([
                    ActionGroup::make([
                        Tables\Actions\ViewAction::make(),
                        Tables\Actions\EditAction::make(),
                    ])
                        ->dropdown(false),
                        Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->action( function (BookingTransaction $record) {
                            $record->is_paid = true;
                            $record->save();
        
                            Notification::make()
                            ->title('Transaction Approve')
                            ->success()
                            ->body('Transaction has been approved')
                            ->send();
                        })
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (BookingTransaction $record) => !$record->is_paid),
                        Tables\Actions\DeleteAction::make()
                        ->visible(fn (BookingTransaction $record) => $record->is_paid),
                ])
                    ->icon('heroicon-m-bars-3')
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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
