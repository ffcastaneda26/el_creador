<?php

namespace App\Filament\Asesor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\State;
use App\Models\Country;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Municipality;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Asesor\Resources\OrderResource\Pages;
use App\Filament\Asesor\Resources\OrderResource\RelationManagers;
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
                                    Forms\Components\TextInput::make('tax')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->translateLabel()
                                        ->disabled(),
                                    Forms\Components\TextInput::make('discount')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->reactive()
                                        ->translateLabel()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn(Set $set, Get $get) => OrderResource::calculateTotals($set, $get)),
                                    Forms\Components\TextInput::make('total')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->translateLabel()
                                        ->disabled()
                                        ->afterStateUpdated(fn(Set $set, Get $get) => OrderResource::calculateTotals($set, $get)),
                                    Forms\Components\TextInput::make('advance')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->translateLabel()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                            $set('pending_balance', round($get('total') - $get('advance'), 2));
                                        }),
                                    Forms\Components\TextInput::make('pending_balance')
                                        ->required()
                                        ->numeric()
                                        ->default(0.00)
                                        ->translateLabel()
                                        ->disabled(),
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
                                        ->translateLabel(),
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
                                ])->disabled(fn(Get $get) => !$get('client_id'))
                                    ->inlineLabel(),
                                Group::make()->schema([
                                    Forms\Components\TextInput::make('address')
                                        ->maxLength(100)
                                        ->translateLabel(),
                                    Forms\Components\TextInput::make('colony')
                                        ->maxLength(100)
                                        ->translateLabel(),
                                    Forms\Components\Textarea::make('references')
                                        ->columnSpanFull()
                                        ->translateLabel(),
                                ])->disabled(fn(Get $get) => !$get('client_id'))
                                    ->inlineLabel()
                            ])->columns(2)
                            ->visible(fn(Get $get) => $get('client_id')),
                    ])->columnSpanFull(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
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
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('date_approved')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->date('d M y'),
                Tables\Columns\TextColumn::make('subtotal')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('advance')
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('pending_balance')
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd()
                    ->label(__('Pending')),

                Tables\Columns\TextColumn::make('tax')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
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
        $tax = 0;
        if ($get('require_invoice')) {
            $tax = round($get('subtotal') * 0.16, 2);
        }
        $set('tax', $tax);
        $set('total', round($get('subtotal') + $tax - $get('discount'), 2));
        $set('pending_balance', round($get('total') - $get('advance'), 2));
    }
}
