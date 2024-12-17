<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use App\Models\Country;
use App\Models\Municipality;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';

    protected static ?int $navigationSort = 13;

    // protected static ?string $cluster = Geographics::class;
    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }
    public static function getModelLabel(): string
    {
        return __('City');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Cities');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('country_id')
                    ->relationship(
                            name: 'country',
                            titleAttribute: 'country',
                            modifyQueryUsing: fn (Builder $query) => $query->where('include',1),
                        )
                    ->required()
                    ->reactive()
                    ->preload()
                    ->default(135)
                    ->searchable(['country', 'code'])
                    ->translateLabel()
                    ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),

                Select::make('state_id')
                    ->translateLabel()
                    ->required()
                    ->reactive()
                    ->options(function (callable $get) {
                        $country = Country::find($get('country_id'));
                        if (!$country) {
                            return;
                        }
                        return $country->states->pluck('name', 'id');
                    })
                    ->afterStateUpdated(fn (callable $set) => $set('municipality_id', null)),
                Select::make('municipality_id')
                    ->translateLabel()
                    ->required()
                    ->reactive()
                    ->options(function (callable $get) {
                        $state = State::find($get('state_id'));
                        if (!$state) {
                            return;
                        }
                        return $state->cities->pluck('name', 'id');
                    }),
               TextInput::make('name')
                    ->required()
                    ->label(__('City'))
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('country.country')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('state.name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('municipality.name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('name')
                        ->searchable()
                        ->sortable()
                        ->label(__('City')),
            ])
            // TODO:: Hacer los select depndientes
            ->filters([
                SelectFilter::make('country')
                    ->translateLabel()
                    ->relationship('country', 'country'),
                SelectFilter::make('state')
                    ->translateLabel()
                    ->relationship('state', 'name'),
                SelectFilter::make('municipality')
                    ->translateLabel()
                    ->relationship('municipality', 'name')
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }


}
