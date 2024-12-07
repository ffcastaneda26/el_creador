<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParteResource\Pages;
use App\Filament\Resources\ParteResource\RelationManagers;
use App\Models\Parte;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParteResource extends Resource
{
    protected static ?string $model = Parte::class;

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';
    protected static ?int $navigationSort = 12;

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }
    public static function getModelLabel(): string
    {
        return __('Part');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Parts');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(30)
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Toggle::make('color')
                    ->required(),
                Forms\Components\Toggle::make('material')
                    ->required(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Part'))
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('color'),
                Tables\Columns\ToggleColumn::make('material'),
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
            'index' => Pages\ListPartes::route('/'),
            'create' => Pages\CreateParte::route('/create'),
            'edit' => Pages\EditParte::route('/{record}/edit'),
        ];
    }
}
