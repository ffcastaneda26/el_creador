<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\State;
use App\Models\Client;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClientResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Municipality;
use App\Models\Zipcode;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';


    protected static ?int $navigationSort = 20;

    // protected static ?string $cluster = Geographics::class;
    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }
    public static function getModelLabel(): string
    {
        return __('Client');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Clients');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->label(__('Full Name'))
                        ->maxLength(100),
                    TextInput::make('email')
                        ->translateLabel()
                        ->maxLength(100),
                    TextInput::make('rfc')
                        ->translateLabel()
                        ->maxLength(13)
                        ->minLength(13),
                    Radio::make('type')
                        ->inline()
                        ->options([
                            'Física'=> 'Física',
                            'Moral' => 'Moral',
                        ])->label(__('Type Person')),
                    Section::make()->schema([
                        TextInput::make('phone')
                        ->translateLabel()
                        ->maxLength(15),
                    TextInput::make('mobile')
                        ->translateLabel()
                        ->nullable()
                        ->maxLength(15),
                    TextInput::make('zipcode')
                        ->translateLabel()
                        ->numeric()
                        ->maxLength(5)
                        ->minLength(5),
                    ])->columns(3),
                    Section::make()->schema([
                        TextInput::make('curp')
                            ->translateLabel()
                            ->nullable()
                            ->minLength(18)
                            ->maxLength(18)
                            ->alphaNum()
                        ->regex('/^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$/'),
                        TextInput::make('ine')
                            ->translateLabel()
                            ->nullable()
                            ->minLength(13)
                            ->maxLength(13),
                        ])->columns(2),

                ])->columns(2),


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
                                return $state->municipalities->sortby('name')->pluck('name', 'id');
                            })->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                        Select::make('city_id')
                            ->translateLabel()
                            ->required()
                            ->options(function (callable $get) {
                                $municipality = Municipality::find($get('municipality_id'));
                                if (!$municipality) {
                                    return;
                                }
                                return $municipality->cities->pluck('name', 'id');
                            }),
                            TextInput::make('address')
                            ->translateLabel()
                            ->required()
                            ->maxLength(100),
                        TextInput::make('colony')
                                ->translateLabel()
                                ->required()
                                ->maxLength(100),
                ])->columns(2),

                Group::make()->schema([
                    MarkdownEditor::make('notes')
                    ->translateLabel()
                    ->columnSpan(2),
                ]),
                Group::make()->schema([
                    MarkdownEditor::make('references')
                    ->translateLabel()
                    ->columnSpan(2),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    TextColumn::make('mobile')
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
                        ->label('RFC')
                        ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('curp')
                        ->searchable()
                        ->sortable()
                        ->label('CURP')
                         ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ine')
                        ->searchable()
                        ->sortable()
                        ->label('INE')
                        ->toggleable(isToggledHiddenByDefault: true)

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
                Tables\Actions\Action::make(__('Private Notice'))
                ->icon('heroicon-o-document')
                ->url(fn (Client $record) => route('student.pdf.download', $record))
                ->openUrlInNewTab()
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
