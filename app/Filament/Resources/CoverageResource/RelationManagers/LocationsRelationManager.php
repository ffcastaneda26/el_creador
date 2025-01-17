<?php

namespace App\Filament\Resources\CoverageResource\RelationManagers;

use App\Models\Country;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'locations';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->options(Country::all()->where('include', 1)->pluck('country', 'id'))
                    ->required()
                    ->reactive()
                    ->preload()
                    ->default(135)
                    ->searchable(['country', 'code'])
                    ->label(__('Country'))
                    ->afterStateUpdated(fn(callable $set) => $set('state_id', null)),
                Forms\Components\Select::make('state_id')
                    ->translateLabel()
                    ->required()
                    ->reactive()
                    ->options(function (callable $get) {
                        $country = Country::find($get('country_id'));
                        if (!$country) {
                            return;
                        }
                        return $country->states->pluck('name', 'id');
                    })->afterStateUpdated(fn(callable $set) => $set('municipality_id', null)),
                Forms\Components\Select::make('municipality_id')
                    ->translateLabel()
                    ->required()
                    ->reactive()
                    ->options(function (callable $get) {
                        $state = State::find($get('state_id'));
                        if (!$state) {
                            return;
                        }
                        return $state->municipalities->sortby('name')->pluck('name', 'id');
                    }),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__("Locations"))
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('municipality.country.country')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('municipality.state.name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('municipality.name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('Add Location')),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
