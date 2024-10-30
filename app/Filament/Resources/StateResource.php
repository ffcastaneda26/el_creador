<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StateResource\Pages;
use App\Filament\Resources\StateResource\RelationManagers;
use App\Models\Country;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class StateResource extends Resource
{
    protected static ?string $model = State::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    protected static ?int $navigationSort = 5;

    // protected static ?string $cluster = Geographics::class;
    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }
    public static function getModelLabel(): string
    {
        return __('State');
    }


    public static function getPluralLabel(): ?string
    {
        return __('States');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(50)
                    ->minLength(5)
                    ->translateLabel(),
                TextInput::make('abbreviated')
                    ->required()
                    ->maxLength(5)
                    ->minLength(2)
                    ->translateLabel(),
                Select::make('country_id')
                    ->relationship(
                            name: 'country',
                            titleAttribute: 'country',
                            modifyQueryUsing: fn (Builder $query) => $query->where('include',1),
                        )
                    ->required()
                     ->preload()
                     ->default(135)
                    ->searchable(['country', 'code'])
                    ->translateLabel(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('country.country')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('abbreviated')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(Auth::user()->isSuperAdmin())
                    ->disabled(!Auth::user()->isSuperAdmin())
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
            'index' => Pages\ListStates::route('/'),
            'create' => Pages\CreateState::route('/create'),
            'edit' => Pages\EditState::route('/{record}/edit'),
        ];
    }
}
