<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Coverage;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CoverageResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CoverageResource\RelationManagers;
use App\Filament\Resources\CoverageResource\RelationManagers\LocationsRelationManager;

class CoverageResource extends Resource
{
    protected static ?string $model = Coverage::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 16;


    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }
    public static function getModelLabel(): string
    {
        return __('Coverage');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Coverages');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->translateLabel(),
                Forms\Components\TextInput::make('distance')
                    ->numeric()
                    ->default(0)
                    ->translateLabel(),
                Forms\Components\TextInput::make('fee')
                    ->default(0.00)
                    ->required()
                    ->translateLabel()
                    ->live(onBlur: true)
                    ->inputMode('decimal'),
                Forms\Components\Textarea::make('notes')
                    ->translateLabel(),

            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('distance')
                    ->numeric(decimalPlaces: 0, decimalSeparator: '.', thousandsSeparator: ',')
                    ->sortable()
                    ->translateLabel()
                    ->prefix('A ')
                    ->suffix(' Kms'),
                Tables\Columns\TextColumn::make('fee')
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ',')
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('notes')
                    ->limit(50)
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('locations_count')
                    ->label(__('Total Locations'))
                    ->counts('locations')
                    // ->visible(false),
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('locations.municipality.name')
                    ->searchable()
                    ->sortable()
                    ->listWithLineBreaks()
                    ->label(__('Locations'))
                    ->toggleable(isToggledHiddenByDefault: true),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            LocationsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoverages::route('/'),
            'create' => Pages\CreateCoverage::route('/create'),
            'edit' => Pages\EditCoverage::route('/{record}/edit'),
        ];
    }
}
