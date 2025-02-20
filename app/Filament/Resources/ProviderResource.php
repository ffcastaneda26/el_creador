<?php

namespace App\Filament\Resources;

use App\Models\Municipality;
use Filament\Forms;
use Filament\Tables;
use App\Models\Provider;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProviderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProviderResource\RelationManagers;
use App\Models\Country;
use App\Models\State;

class ProviderResource extends Resource
{
    protected static ?string $model = Provider::class;


    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 26;

    // protected static ?string $cluster = Geographics::class;
    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }
    public static function getModelLabel(): string
    {
        return __('Provider');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Providers');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label(__('Full Name'))
                        ->maxLength(100)
                        ->columnSpanFull(),

                    Forms\Components\Section::make()->schema([
                        Forms\Components\TextInput::make('email')
                            ->translateLabel()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('rfc')
                            ->translateLabel()
                            ->maxLength(13)
                            ->minLength(13),
                        Forms\Components\Radio::make('type')
                            ->inline()
                            ->options([
                                'Física' => 'Física',
                                'Moral' => 'Moral',
                            ])->label(__('Type Person')),
                    ])->columns(3),



                    Forms\Components\Section::make()->schema([
                        Forms\Components\TextInput::make('phone')
                            ->translateLabel()
                            ->maxLength(15),
                        Forms\Components\TextInput::make('zipcode')
                            ->translateLabel()
                            ->numeric()
                            ->maxLength(5)
                            ->minLength(5),

                        Forms\Components\Toggle::make('active')
                            ->default(true),
                        Forms\Components\MarkdownEditor::make('notes')
                            ->translateLabel()
                            ->columnSpanFull(),
                    ])->columns(3),

                ])->columns(2),


                Forms\Components\Group::make()->schema([
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
                        })->afterStateUpdated(fn(callable $set) => $set('city_id', null)),

                    Forms\Components\Select::make('city_id')
                        ->translateLabel()
                        ->required()
                        ->options(function (callable $get) {
                            $municipality = Municipality::find($get('municipality_id'));
                            if (!$municipality) {
                                return;
                            }
                            return $municipality->cities->pluck('name', 'id');
                        }),
                    Forms\Components\TextInput::make('address')
                        ->translateLabel()
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('colony')
                        ->translateLabel()
                        ->required()
                        ->maxLength(100),
                    Forms\Components\Group::make()->schema([
                        Forms\Components\MarkdownEditor::make('references')
                            ->translateLabel()
                    ])
                        ->columnSpanFull(),
                ])->columns(2),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('country.country'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('Name')),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('phone')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->label(__('Person')),
                TextColumn::make('rfc')
                    ->searchable()
                    ->sortable()
                    ->label('RFC'),
            ])
            ->filters([
                SelectFilter::make('country')
                    ->translateLabel()
                    ->relationship('country', 'country', fn(Builder $query) => $query->wherehas('providers')),

                SelectFilter::make('state')
                    ->translateLabel()
                    ->relationship('state', 'name', fn(Builder $query) => $query->wherehas('providers')),
                SelectFilter::make('municipality')
                    ->translateLabel()
                    ->relationship('municipality', 'name', fn(Builder $query) => $query->wherehas('providers')),
                SelectFilter::make('city')
                    ->translateLabel()
                    ->relationship('city', 'name', fn(Builder $query) => $query->wherehas('providers'))
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
            'index' => Pages\ListProviders::route('/'),
            'create' => Pages\CreateProvider::route('/create'),
            'edit' => Pages\EditProvider::route('/{record}/edit'),
        ];
    }
}
