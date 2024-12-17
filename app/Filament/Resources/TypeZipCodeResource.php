<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TypeZipCodeResource\Pages;
use App\Filament\Resources\TypeZipCodeResource\RelationManagers;
use App\Models\TypeZipCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TypeZipCodeResource extends Resource
{
    protected static ?string $model = TypeZipCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';

    protected static ?int $navigationSort = 14;

    // protected static ?string $cluster = Geographics::class;
    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }
    public static function getModelLabel(): string
    {
        return __('Type Zipcode');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Types Zipcode');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->translateLabel()
                    ->maxLength(30),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->translateLabel()
                    ->searchable(),
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
            'index' => Pages\ListTypeZipCodes::route('/'),
            'create' => Pages\CreateTypeZipCode::route('/create'),
            'edit' => Pages\EditTypeZipCode::route('/{record}/edit'),
        ];
    }
}
