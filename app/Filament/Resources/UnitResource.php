<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Unit;
use Filament\Tables;
use App\Enums\UnitType;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\UnitTypeEnum;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UnitResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UnitResource\RelationManagers;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 23;


    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }

    public static function getModelLabel(): string
    {
        return __('Unit of Measurement');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Units of Measurement');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->translateLabel()
                    ->maxLength(255),
                TextInput::make('symbol')
                    ->translateLabel()
                    ->maxLength(10),
                Select::make('type')
                    ->options(UnitType::class)
                    ->translateLabel(),
                Textarea::make('description')
                    ->translateLabel()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('symbol')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('description')
                    ->searchable()
                    ->sortable()
                    ->translateLabel()
                    ->limit(50),
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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnit::route('/create'),
            'edit' => Pages\EditUnit::route('/{record}/edit'),
        ];
    }
}
