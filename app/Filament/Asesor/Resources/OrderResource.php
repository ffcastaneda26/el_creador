<?php

namespace App\Filament\Asesor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\State;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use App\Filament\Asesor\Resources\OrderResource\Pages;
use App\Models\Client;
use App\Models\Zipcode;
use Filament\Forms\Get;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';

    protected static ?int $navigationSort = 32;

    public static function getNavigationGroup(): string
    {
        return __('Sales');
    }

    public static function getModelLabel(): string
    {
        return __('Purchase Order');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Purchase Orders');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('Generals'))
                            ->schema([
                                Group::make()->schema([
                                    Forms\Components\Select::make('client_id')
                                        ->relationship('client', 'name')
                                        ->required()
                                        ->translateLabel()
                                        ->reactive()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn(Set $set, Get $get) => OrderResource::getClient($set, $get, $get('client_id')))
                                        ->columnSpan(2),
                                    Forms\Components\DatePicker::make('date')
                                        ->required()
                                        ->translateLabel()
                                        ->format('Y-m-d')
                                        ->maxDate(now())
                                        ->disabled(fn(Get $get) => !$get('client_id')),
                                    Forms\Components\Toggle::make('approved')
                                        ->required()
                                        ->translateLabel()
                                        ->reactive()
                                        ->disabled(fn(Get $get) => !$get('client_id')),
                                    Forms\Components\DatePicker::make('date_approved')
                                        ->translateLabel()
                                        ->format('Y-m-d')
                                        ->minDate(fn(Get $get) => $get('date'))
                                        ->required(fn(Get $get) => $get('approved'))
                                        ->visible(fn(Get $get) => $get('approved'))
                                        ->disabled(fn(Get $get) => !$get('client_id') || !$get('approved')),
                                ])->columns(5),

                                Group::make()->schema([
                                    Forms\Components\Toggle::make('require_invoice')
                                        ->required()
                                        ->translateLabel()
                                        ->live(onBlur: true)
                                        ->reactive()
                                        ->inline()
                                        ->disabled(fn(Get $get) => !$get('client_id'))
                                        ->afterStateUpdated(fn(Set $set, Get $get) => OrderResource::calculateTotals($set, $get)),
                                    Forms\Components\DatePicker::make('delivery_date')
                                        ->translateLabel()
                                        ->format('Y-m-d')
                                        ->minDate(fn(Get $get) => $get('date')),
                                    Forms\Components\DatePicker::make('payment_promise_date')
                                        ->translateLabel()
                                        ->minDate(now())
                                        ->maxDate(fn(Get $get) => $get('delivery_date')),
                                ])->disabled(fn(Get $get) => !$get('client_id'))
                                    ->inlineLabel()
                                    ->columns(3),

                                Group::make()->schema([
                                    Forms\Components\TextInput::make('subtotal')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->translateLabel()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn(Set $set, Get $get) => OrderResource::calculateTotals($set, $get)),
                                    Forms\Components\TextInput::make('discount')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->reactive()
                                        ->translateLabel()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn(Set $set, Get $get) => OrderResource::calculateTotals($set, $get)),

                                    Forms\Components\TextInput::make('tax')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->translateLabel()
                                        ->readOnly(),
                                    Forms\Components\TextInput::make('retencion_isr')
                                        ->required()
                                        ->translateLabel()
                                        ->inputMode('decimal')
                                        ->readOnly(),
                                    Forms\Components\TextInput::make('total')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->translateLabel()
                                        ->readOnly()
                                        ->afterStateUpdated(fn(Set $set, Get $get) => OrderResource::calculateTotals($set, $get)),
                                    Forms\Components\TextInput::make('advance')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->translateLabel()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn(Set $set, Get $get) => OrderResource::calculateTotals($set, $get)),
                                    Forms\Components\TextInput::make('pending_balance')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->translateLabel()
                                        ->readOnly(),
                                ])->disabled(fn(Get $get) => !$get('client_id'))
                                    ->columns(7),

                                // ----
                                Group::make()->schema([
                                    Forms\Components\Textarea::make('notes')
                                        ->translateLabel()
                                        ->columnSpan(3),
                                ])->disabled(fn(Get $get) => !$get('client_id'))
                                    ->columns(3),

                            ]),
                        Tabs\Tab::make(__('Delivery'))
                            ->schema([
                                Group::make()->schema([
                                    Forms\Components\TextInput::make('zipcode')
                                        ->maxLength(5)
                                        ->translateLabel()
                                        ->live(onBlur: true)
                                        ->afterstateupdated(function (callable $get, callable $set) {
                                            $set('country', null);
                                            $set('state', null);
                                            $set('municipality', null);
                                            $set('city', null);

                                            $zipcode = OrderResource::getZipcode($get('zipcode'));
                                            if ($zipcode) {
                                                // dd($zipcode);
                                                $set('country', $zipcode->country);
                                                $set('state', $zipcode->state);
                                                $set('municipality', $zipcode->municipality);
                                                $set('city', $zipcode->city);
                                                $colonies = OrderResource::getColonies($get('zipcode'));
                                                $colonyvalue = $get('colony');
                                                if ($colonyvalue || strlen($colonyvalue) > 0) {
                                                    if ($colonyvalue && is_array($colonies) && in_array($colonyvalue, array_keys($colonies))) {
                                                        return;
                                                    } else {
                                                        $set('colony', null);
                                                    }
                                                }
                                            }
                                        }),

                                    Forms\Components\TextInput::make('country')
                                        ->translateLabel()
                                        ->disabled(),
                                    Forms\Components\TextInput::make('state')
                                        ->translateLabel()
                                        ->disabled(),
                                    Forms\Components\TextInput::make('municipality')
                                        ->translateLabel()
                                        ->disabled(),
                                    Forms\Components\TextInput::make('city')
                                        ->translateLabel()
                                        ->disabled(),
                                    Forms\Components\Select::make('colony')
                                        ->translateLabel()
                                        // ->required()
                                        ->searchable()
                                        ->disabled(fn(Get $get): bool => !OrderResource::zipcodeExists($get('zipcode')))
                                        ->options(function (callable $get, callable $set) {
                                            $colonies = OrderResource::getColonies($get('zipcode'));
                                            if (count($colonies)) {
                                                return $colonies;
                                            }
                                            $set('colony', null);
                                            return null;

                                        }),


                                ])->columns(2),
                                Group::make()->schema([
                                    Forms\Components\TextInput::make('street')
                                        ->maxLength(100)
                                        ->translateLabel(),
                                    Forms\Components\TextInput::make('number')
                                        ->maxLength(100)
                                        ->translateLabel(),
                                    Forms\Components\TextInput::make('interior_number')
                                        ->maxLength(100)
                                        ->translateLabel(),
                                    Forms\Components\Textarea::make('references')
                                        ->columnSpanFull()
                                        ->rows(3)
                                        ->translateLabel(),
                                ])->disabled(fn(Get $get) => !$get('client_id'))
                                    ->inlineLabel()
                            ])->columns(2),
                    ])->columnSpanFull()
                    ->afterStateHydrated(function (Set $set, Get $get) {
                        OrderResource::calculateTotals($set, $get);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.full_name')
                    ->numeric()
                    ->sortable()
                    ->label(__('Client')),
                Tables\Columns\TextColumn::make('date')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->date('d M y'),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_approved')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->date('d M y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('subtotal')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('discount')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd()
                    ->translateLabel()
                    ->visible(fn($record): bool => $record !== null && $record->discount > 0),
                Tables\Columns\TextColumn::make('tax')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('retencion_isr')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('advance')
                    ->sortable()
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('pending_balance')
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd()
                    ->label(__('Pending')),



                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_date')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->date('d M y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('colony')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('zipcode')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country.id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('municipality.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('city.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('require_invoice')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_promise_date')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->date('d M y')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make(__('Contrat'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->url(fn(Order $record) => route('pdf-document', [$record, 'contrato']))
                    ->openUrlInNewTab()
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function calculateTotals(Set $set, Get $get)
    {
        $require_invoice = $get('require_invoice');
        $subtotal = round(floatval($get('subtotal')), 2);
        $descuento = round(floatval($get('discount')), 2);
        $anticipo = round(floatval($get('advance')), 2);
        $retencion_isr = 00.00;
        $tax = 00.00;
        if ($require_invoice) {
            $percentage_iva = round(env('PERCENTAGE_IVA', 16) / 100, 2);
            $percentage_retencion = env('PERCENTAGE_RETENCION_ISR', 1.25);
            $base_retencion = round($subtotal - $descuento, 2);
            $tax = round($base_retencion * $percentage_iva, 2);
            $retencion_isr = round($base_retencion * ($percentage_retencion / 100), 2);
        }

        $total = round($subtotal - $descuento + $tax - $retencion_isr, 2);
        $pending_balance = round($total - $anticipo, 2);

        $set('tax', $tax);
        $set('retencion_isr', $retencion_isr);
        $set('total', $total);
        $set('pending_balance', $pending_balance);
    }

    public static function getClient(Set $set, Get $get, $client_id)
    {
        $client = Client::find($client_id);
        $set('zipcode', $client->zipcode);
        $set('street', $client->street);
        $set('number', $client->number);
        $set('interior_number', $client->interior_number);
        $set('colony', $client->colony);
        $zipcode = Zipcode::where('zipcode', $client->zipcode)->first();
        if ($zipcode) {
            $set('country', $zipcode->country);
            $set('state', $zipcode->state);
            $set('municipality', $zipcode->municipality);
            $set('city', $zipcode->city);
        }
        $set('references', $client->references);
    }

    public static function getZipcode($zipcode)
    {
        $zipcode = Zipcode::where('zipcode', $zipcode)->first();
        return $zipcode;
    }


    public static function zipcodeExists($zipcode)
    {
        return Zipcode::where('zipcode', $zipcode)->exists();
    }


    public static function getColonies($zipcode)
    {
        $zipcode = Zipcode::where('zipcode', $zipcode)->pluck('name', 'name')->toArray();
        return $zipcode;
    }
}
