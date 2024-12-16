<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WareHouseResource\Pages;
use App\Filament\Resources\WareHouseResource\RelationManagers;
use App\Models\WareHouse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WareHouseResource extends Resource
{
    protected static ?string $model = WareHouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }
    public static function getModelLabel(): string
    {
        return __('Warehouse');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Warehouses');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->translateLabel()
                    ->unique(ignoreRecord:true)
                    ->maxLength(100),
                Forms\Components\TextInput::make('short')
                    ->unique(ignoreRecord:true)
                    ->required()
                    ->translateLabel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->translateLabel()
                    ->maxLength(15),
                Forms\Components\TextInput::make('rfc')
                    ->maxLength(13)
                    ->translateLabel(),
                Forms\Components\Toggle::make('active')
                    ->translateLabel()
                    ->required(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('short')
                    ->sortable()
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable()
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rfc')
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->rfc ?? 'N/A')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListWareHouses::route('/'),
            'create' => Pages\CreateWareHouse::route('/create'),
            'edit' => Pages\EditWareHouse::route('/{record}/edit'),
        ];
    }
}
