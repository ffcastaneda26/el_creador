<?php

namespace App\Filament\Resources;
/** 
 * +-------------+---------+--------------------------------------------------------------------------------------------+
 * |  Fecha      | Author  | Descripción                                                                                |
 * +-------------+---------+--------------------------------------------------------------------------------------------+
 * | 17-Sep-2025 | FCO     | Se agrega el campo para correo electrónico en el formulario                                | 
 * +-------------+---------+--------------------------------------------------------------------------------------------+
 */
use Filament\Forms;
use Filament\Tables;
use App\Models\State;
use App\Models\Client;
use App\Models\Country;
use App\Models\Zipcode;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Municipality;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ClientResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClientResource\RelationManagers;
use Filament\Forms\Get;
use Symfony\Contracts\Service\Attribute\Required;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 30;

    // protected static ?string $cluster = Geographics::class;
    public static function getNavigationGroup(): string
    {
        return __('Sales');
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
                    Section::make()->schema([
                        Radio::make('type')
                            ->inline()
                            ->reactive()
                            ->options([
                                'Física' => 'Física',
                                'Moral' => 'Moral',
                                'Sin Efectos Fiscales' => 'Sin Efectos'
                            ])->label(__('Type Person'))
                            ->default('Física'),
                    ])->columnSpanFull(),

                    Section::make()->schema([
                        TextInput::make('name')
                            ->required()
                            ->label(__('Name'))
                            ->maxLength(100),
                        TextInput::make('last_name')
                            ->required(fn(Get $get): bool => $get('type') === 'Física' ||  $get('type') === 'Sin Efectos Fiscales')
                            ->label(__('Last Name'))
                            ->maxLength(100),
                        TextInput::make('mother_surname')
                            ->label(__('Mother Surname'))
                            ->maxLength(100),
                        TextInput::make('company_name')
                            ->required(fn(Get $get): bool => $get('type') === 'Moral')
                            ->label(__('Company Name'))
                            ->maxLength(100)
                            ->columnSpanFull(),

                    ])->visible(fn(Get $get): bool => $get('type') === 'Física' ||  $get('type') === 'Sin Efectos Fiscales')
                        ->columns(3),

                    Section::make()->schema([
                        TextInput::make('name')
                            ->required()
                            ->label(__('Full Name'))
                            ->maxLength(100)
                            ->columnSpanFull(),
                        TextInput::make('company_name')
                            ->required()
                            ->label(__('Company Name'))
                            ->maxLength(100)
                            ->columnSpanFull(),
                    ])->visible(fn(Get $get): bool => $get('type') === 'Moral')
                        ->columnSpanFull(),

                    Section::make()->schema([
                        // Radio::make('tax_type')
                        //     ->options([
                        //         'Iva' => 'Iva',
                        //         'Retención' => 'Retención',
                        //     ])
                        //     ->translateLabel(),
                        //    Toggle::make('iva')->inline(),
                        //    Toggle::make('retencion')->inline(),

                        TextInput::make('rfc')
                            ->translateLabel()
                            ->maxLength(fn(Get $get) => $get('type') === 'Física' ? 13 : 12)
                            ->minLength(fn(Get $get) => $get('type') === 'Física' ? 13 : 12)
                            ->required(fn(Get $get): bool => $get('type') === 'Física' ||  $get('type') === 'Moral'),
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
                        TextInput::make('email')
                            ->translateLabel()
                            ->nullable()
                            ->email()
                            ->maxLength(100),
                    ])->columns(2),

                ])->columns(2),


                Group::make()->schema([
                    Section::make()->schema([
                        TextInput::make('phone')
                            ->translateLabel()
                            ->maxLength(15),
                        TextInput::make('mobile')
                            ->translateLabel()
                            ->nullable()
                            ->maxLength(15),

                        TextInput::make('zipcode')
                            ->required()
                            ->translateLabel()
                            ->numeric()
                            ->reactive()
                            ->maxLength(5)
                            ->minLength(5)
                            ->afterstateupdated(function ($operation, callable $get, callable $set) {
                                $set('country_id', null);
                                $set('state_id', null);
                                $set('municipality_id', null);
                                $set('city_id', null);

                                $zipcode = ClientResource::getZipcode($get('zipcode'));
                                if ($zipcode) {
                                    $set('country_id', $zipcode->country_id);
                                    $set('state_id', $zipcode->state_id);
                                    $set('municipality_id', $zipcode->municipality_id);
                                    $set('city_id', $zipcode->city_id);
                                    $colonies = ClientResource::getColonies($get('zipcode'));
                                    $colonyvalue = $get('colony');
                                    if ($colonyvalue || strlen($colonyvalue) > 0) {
                                        if ($colonyvalue && is_array($colonies) && in_array($colonyvalue, array_keys($colonies))) {
                                            return;
                                        } else {
                                            $set('colony', null);
                                        }
                                    }
                                }
                            })
                    ])->columns(3),

                    Section::make()->schema([
                        TextInput::make('country_id')
                            ->hidden(),
                        Select::make('state_id')
                            ->translateLabel()
                            ->required()
                            ->disabled()
                            ->options(function (callable $get, callable $set) {
                                if ($get('zipcode') != null && strlen($get('zipcode')) == 5) {
                                    $zipcode = ClientResource::getZipcode($get('zipcode'));
                                    if ($zipcode) {
                                        $set('country_id', $zipcode->country_id);
                                    }
                                }
                                $country = Country::find($get('country_id'));
                                if (!$country) {
                                    return;
                                }
                                return $country->states->pluck('name', 'id');
                            }),

                        Select::make('municipality_id')
                            // ->translateLabel()
                            ->label(function (Get $get) {
                                return $get('state_id') === 9 ? __('Delegation') : __('Municipality');
                            })
                            ->required()
                            ->disabled()
                            ->options(function (callable $get, callable $set) {
                                if ($get('zipcode') != null && strlen($get('zipcode')) == 5) {
                                    $zipcode = ClientResource::getZipcode($get('zipcode'));
                                    if ($zipcode) {
                                        $set('state', $zipcode->state_id);
                                    }
                                }
                                $state = State::find($get('state_id'));
                                if (!$state) {
                                    return;
                                }
                                return $state->municipalities->sortby('name')->pluck('name', 'id');
                            }),

                        Select::make('city_id')
                            ->translateLabel()
                            ->required()
                            ->disabled()
                            ->options(function (callable $get, callable $set) {
                                if ($get('zipcode') != null && strlen($get('zipcode')) == 5) {
                                    $zipcode = ClientResource::getZipcode($get('zipcode'));
                                    if ($zipcode) {
                                        $set('municipality_id', $zipcode->municipality_id);
                                    }
                                }
                                $municipality = Municipality::find($get('municipality_id'));
                                if (!$municipality) {
                                    return;
                                }

                                return $municipality->cities->pluck('name', 'id');
                            })->afterStateUpdated(function ($operation, $state, callable $set, callable $get) {
                                $colonies = ClientResource::getColonies($get('zipcode'));
                                $colonyValue = $get('colony');
                                if ($get('colony') || strlen($get('colony') > 0)) {
                                    if ($colonyValue && is_array($colonies) && in_array($colonyValue, array_keys($colonies))) {
                                        return;
                                    } else {
                                        $set('colony', null);
                                    }
                                }
                            }),
                        Select::make('colony')
                            ->translateLabel()
                            ->required()
                            ->searchable()
                            ->disabled(fn(Get $get): bool => !ClientResource::existsZipcode($get('zipcode')))
                            ->options(function (callable $get, callable $set) {
                                return ClientResource::getColonies($get('zipcode'));
                            })->columnSpanFull(),

                    ])->visible(fn(Get $get): bool => $get('zipcode') != null && strlen($get('zipcode')) == 5)
                        ->columns(3),



                    Section::make()->schema([
                        TextInput::make('colony')
                            ->translateLabel()
                            ->required()
                            ->visible(fn(Get $get): bool => $get('zipcode') == null || strlen($get('zipcode')) != 5)
                            ->columnSpanFull(),
                        TextInput::make('street')
                            ->translateLabel()
                            ->required()
                            ->maxLength(100)
                            ->translateLabel(),
                        TextInput::make('number')
                            ->translateLabel()
                            ->required()
                            ->maxLength(5)
                            ->translateLabel(),
                        TextInput::make('interior_number')
                            ->translateLabel()
                            ->maxLength(5)
                            ->translateLabel(),
                    ])->columns(3),



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
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->label(__('ID')),
                TextColumn::make('full_name')
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
                    ->toggleable(isToggledHiddenByDefault: true),


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
                Tables\Actions\EditAction::make()
                    ->button()
                    ->color('warning')
                    ->size('xs'),
                Tables\Actions\Action::make(__('Notice'))
                    ->button()
                    ->size('xs')
                    ->color('primary')
                    ->icon('heroicon-o-document')
                    ->url(fn(Client $record) => route('pdf-document', [$record, 'aviso']))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->size('xs')
                    ->disabled(function (Client $record) {
                        return $record->cotizations()->exists() || $record->orders()->exists();
                    }),

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

    public static function getZipcode($zipcode)
    {
        $zipcode = Zipcode::where('zipcode', $zipcode)->first();
        return $zipcode;
    }

    public static function getColonies($zipcode)
    {
        $zipcode = Zipcode::where('zipcode', $zipcode)->pluck('name', 'name')->toArray();
        return $zipcode;
    }

    public static function existsZipcode($zipcode)
    {
        return Zipcode::where('zipcode', $zipcode)->exists();
    }
}
