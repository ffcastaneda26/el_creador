<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Zipcode;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ZipcodeResource\Pages;
use App\Models\Country;
use App\Models\Municipality;
use App\Models\State;
use Filament\Forms\Components\Group;

class ZipcodeResource extends Resource
{
    protected static ?string $model = Zipcode::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 15;

    // protected static ?string $cluster = Geographics::class;
    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }
    public static function getModelLabel(): string
    {
        return __('Zipcode');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Zipcodes');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    TextInput::make('zipcode')
                        ->required()
                        ->numeric()
                        ->maxLength(5)
                        ->minLength(5),
                        TextInput::make('name')
                        ->required()
                        ->maxLength(100)
                        ->columnSpan(2),
                    Select::make('type_zipcode_id')
                    ->relationship(
                            name: 'type',
                            titleAttribute: 'type'
                        )
                    ->required()
                    ->preload()
                    ->translateLabel()
                    ->columnSpan(2)

                ])->columns(3),
                Group::make()->schema([
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
                    })->afterStateUpdated(fn (callable $set) => $set('municipality_id', null)),

                Select::make('municipality_id')
                        ->translateLabel()
                        ->required()
                        ->reactive()
                        ->options(function (callable $get) {
                            $state = State::find($get('state_id'));
                            if (!$state) {
                                return;
                            }
                            return $state->municipalities->pluck('name', 'id');
                    })->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                Select::make('city_id')
                    ->translateLabel()
                    ->required()
                    ->reactive()
                    ->options(function (callable $get) {
                        $municipality = Municipality::find($get('municipality_id'));
                        if (!$municipality) {
                            return;
                        }
                        return $municipality->cities->pluck('name', 'id');
                    }),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('country.country')
                    ->searchable()
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('state.name')
                    ->searchable()
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('municipality.name')
                    ->searchable()
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('city.name')
                    ->searchable()
                    ->translateLabel()
                    ->sortable(),
                TextColumn::make('zipcode')
                    ->label(__('Zipcode'))
                    ->searchable(),
                TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type.type')
                    ->searchable()
                    ->translateLabel(),
            ])
            ->filters([
                SelectFilter::make('country')
                    ->translateLabel()
                    ->relationship('country', 'country'),
                SelectFilter::make('state')
                    ->translateLabel()
                    ->relationship('state', 'name'),
                SelectFilter::make('municipality')
                    ->translateLabel()
                    ->relationship('municipality', 'name'),
                SelectFilter::make('city')
                    ->translateLabel()
                    ->relationship('city', 'name')
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
            'index' => Pages\ListZipcodes::route('/'),
            'create' => Pages\CreateZipcode::route('/create'),
            'edit' => Pages\EditZipcode::route('/{record}/edit'),
        ];
    }
}
