<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Municipality;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MunicipalityResource\Pages;
use App\Filament\Resources\MunicipalityResource\RelationManagers;
use App\Models\Country;

class MunicipalityResource extends Resource
{
    protected static ?string $model = Municipality::class;


    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 12;


    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }
    public static function getModelLabel(): string
    {
        return __('Municipality');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Municipalities');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->relationship(
                        name: 'country',
                        titleAttribute: 'country',
                        modifyQueryUsing: fn(Builder $query) => $query->where('include', 1),
                    )
                    ->required()
                    ->reactive()
                    ->preload()
                    ->default(135)
                    ->searchable(['country', 'code'])
                    ->translateLabel()
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

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->translateLabel()
                    ->maxLength(255),
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
                TextColumn::make('state.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('Municipality'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('Country')
                    ->relationship('country',
                                'country',
                                fn(Builder $query) => $query->where('include', 1))  // Only show countries that are included
                    ->translateLabel()
                    ->preload()
                    ->visible(fn() => Country::where('include', 1)->count() > 1),

                SelectFilter::make('state')
                    ->translateLabel()
                    ->relationship('state', 'name')
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
            'index' => Pages\ListMunicipalities::route('/'),
            'create' => Pages\CreateMunicipality::route('/create'),
            'edit' => Pages\EditMunicipality::route('/{record}/edit'),
        ];
    }
}
