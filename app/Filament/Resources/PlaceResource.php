<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlaceResource\Pages;
use App\Filament\Resources\PlaceResource\RelationManagers;
use App\Models\Place;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Resources Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('General Information')
                    ->icon('heroicon-m-information-circle')
                    ->completedIcon('heroicon-m-check')
                    ->description('Information about the place.')
                    ->columns(2)
                        ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->image()
                            ->required()
                            ->directory('places/thumbnails')
                            ->disk('public'),
                        Forms\Components\Select::make('city_id')
                            ->required()
                            ->relationship('city', 'name')
                            ->placeholder('Select a city'),
                        Forms\Components\Select::make('category_id')
                            ->required()
                            ->relationship('category', 'name')
                            ->placeholder('Select a category'),
                        Forms\Components\TextInput::make('rating')
                            ->required()
                            ->placeholder('0-5')
                            ->minValue(0)
                            ->maxValue(5)
                            ->numeric(),
                        Forms\Components\TextInput::make('price')
                            ->prefix('IDR')
                            ->required()
                            ->numeric(),
                        Forms\Components\TimePicker::make('opening_hours')
                            ->required(),
                        Forms\Components\TimePicker::make('closing_hours')
                            ->required(),
                        Forms\Components\Textarea::make('address')
                            ->required()
                            ->rows(3),
                        Forms\Components\RichEditor::make('description')
                            ->required(),
                        ]),
                    Wizard\Step::make('Contact Information')
                        ->icon('heroicon-m-phone-arrow-down-left')
                        ->description('Contact person information.')
                        ->columns(2)
                        ->schema([
                            Forms\Components\TextInput::make('cs_name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('cs_phone')
                                ->required()
                                ->numeric(),
                            Forms\Components\FileUpload::make('cs_avatar')
                                ->image()
                                ->required()
                                ->directory('places/cs_avatars')
                                ->disk('public'),
                        ]),
                    Wizard\Step::make('Fields Information')
                        ->icon('heroicon-m-bars-3-center-left')
                        ->description('List fields available.')
                        ->schema([
                            Repeater::make('Fields')
                                ->relationship('fields')
                                ->columns(2)
                                ->schema([
                                    Forms\Components\FileUpload::make('thumbnail')
                                        ->image()
                                        ->required()
                                        ->columnSpanFull()
                                        ->directory('fields/thumbnails')
                                        ->disk('public'),
                                    Forms\Components\TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('price')
                                        ->prefix('IDR')
                                        ->required()
                                        ->numeric(),
                                    Forms\Components\ToggleButtons::make('is_available')
                                        ->options([
                                            '1' => 'Available',
                                            '0' => 'Not Available',
                                        ])
                                        ->required(),
                                    Forms\Components\ToggleButtons::make('is_indoor')
                                        ->options([
                                            '1' => 'Indoor',
                                            '0' => 'Outdoor',
                                        ])
                                        ->required(),
                                    Forms\Components\TextInput::make('floor_type')
                                        ->required()
                                        ->maxLength(255),
                                ]),
                        ]),
                    Wizard\Step::make('Photos Gallery')
                        ->icon('heroicon-m-photo')
                        ->description('Gallery of photos.')
                        ->schema([
                            Repeater::make('Photos')
                                ->relationship('photos')
                                ->columns(1)
                                ->schema([
                                    Forms\Components\FileUpload::make('photo')
                                        ->image()
                                        ->required()
                                        ->directory('places/photos')
                                        ->disk('public'),
                                ])
                                ->grid(2)
                                ->defaultItems(3),
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
            Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable(),
            Tables\Columns\ImageColumn::make('thumbnail')
                ->disk('public'),
            Tables\Columns\TextColumn::make('city.name')
                ->label('City')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('category.name')
                ->label('Category')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('fields_count')
                ->label('Fields Count')
                ->counts('fields')
                ->sortable(),
            Tables\Columns\TextColumn::make('photos_count')
                ->label('Photos Count')
                ->counts('photos')
                ->sortable(),
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
                    Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPlaces::route('/'),
            'create' => Pages\CreatePlace::route('/create'),
            'edit' => Pages\EditPlace::route('/{record}/edit'),
        ];
    }
}
