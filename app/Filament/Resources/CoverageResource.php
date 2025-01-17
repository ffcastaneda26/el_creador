<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoverageResource\Pages;
use App\Filament\Resources\CoverageResource\RelationManagers;
use App\Models\Coverage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(50)
                        ->unique(ignoreRecord: true)
                        ->translateLabel(),
                    Forms\Components\TextInput::make('distance')
                        ->numeric()
                        ->default(0)
                        ->translateLabel(),
                ])->columns(2),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Textarea::make('notes')
                    ->columnSpanFull()
                    ->translateLabel(),
                ])

            ]);
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
            //
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
