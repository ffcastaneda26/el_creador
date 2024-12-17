<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\KeyMovement;
use Filament\Resources\Resource;
use App\Enums\Enums\KeyMovementTypeEnum;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Enums\KeyMovementUsedToEnum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KeyMovementResource\Pages;
use App\Filament\Resources\KeyMovementResource\RelationManagers;
use Filament\Support\Enums\Alignment;

class KeyMovementResource extends Resource
{
    protected static ?string $model = KeyMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';

    protected static ?int $navigationSort = 24;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->translateLabel(),
                Forms\Components\TextInput::make('short')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->translateLabel(),
                    Forms\Components\Toggle::make('require_cost')
                    ->translateLabel()
                ])->columns(3),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Radio::make('type')
                    ->inline()
                    ->translateLabel()
                    ->options(KeyMovementTypeEnum::class)
                    ->required(),
                Forms\Components\Radio::make('used_to')
                    ->inline()
                    ->translateLabel()
                    ->options(KeyMovementUsedToEnum::class)
                    ->required(),
                ]),

            ]);
    }
    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }
    public static function getModelLabel(): string
    {
        return __('Key Movement');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Key Movements');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('short')
                    ->sortable()
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('used_to')
                    ->translateLabel()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('used_to')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\IconColumn::make('require_cost')
                    ->translateLabel()
                    ->alignment(Alignment::Center)
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),

            ])
            ->filters([
                SelectFilter::make('used_to')
                    ->options(KeyMovementUsedToEnum::class)
                    ->translateLabel(),
                SelectFilter::make('type')
                    ->options(KeyMovementTypeEnum::class)
                    ->translateLabel()
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
            'index' => Pages\ListKeyMovements::route('/'),
            'create' => Pages\CreateKeyMovement::route('/create'),
            'edit' => Pages\EditKeyMovement::route('/{record}/edit'),
        ];
    }
}
