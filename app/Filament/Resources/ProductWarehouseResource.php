<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Country;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ProductWarehouse;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductWarehouseResource\Pages;
use App\Filament\Resources\ProductWarehouseResource\RelationManagers;
use Filament\Forms\Set;

class ProductWarehouseResource extends Resource
{
    protected static ?string $model = ProductWarehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('Administrador');
    }

    public static function getNavigationParentItem(): ?string
    {
        return __('Warehouses');
    }
    public static function getNavigationLabel(): string
    {
        return __('Products in the warehouse');
    }


    public static function getModelLabel(): string
    {
        return __('Product In The Warehouse');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Products in the warehouse');
    }
    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Select::make('warehouse_id')
                                    ->relationship('warehouse', 'name')
                                    ->translateLabel()
                                    ->required(),
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->translateLabel()
                                    ->required()
                                    ->rules([
                                        fn(Get $get, string $operation): Closure => function (string $attribute, $value, Closure $fail) use ($get, $operation) {
                                            if ($operation == 'create') {
                                                $exists = ProductWarehouse::where('product_id', $get('product_id'))
                                                    ->where('warehouse_id', $get('warehouse_id'))
                                                    ->exists();
                                                if ($exists) {
                                                    $fail(__('The product already exists in this warehouse'));
                                                }
                                            }
                                        },
                                    ]),

                                Toggle::make('active')
                                    ->default(true),
                            ])->columns(3),
                    ])->columnSpanFull(),
                Group::make()->schema([
                    Section::make()
                        ->schema([
                            TextInput::make('stock')
                                ->required()
                                ->translateLabel(),
                            TextInput::make('stock_min')
                                ->required()
                                ->numeric()
                                ->translateLabel(),
                            TextInput::make('stock_max')
                                ->required()
                                ->gte('stock_min')
                                ->numeric()
                                ->translateLabel(),
                            TextInput::make('stock_reorder')
                                ->gte('stock_min')
                                ->lte('stock_max')
                                ->required()
                                ->numeric()
                                ->translateLabel(),
                            TextInput::make('stock_available')
                                ->required()
                                ->numeric()
                                ->translateLabel()
                                ->default(0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(Set $set, ?string $state, Get $get) => $set('stock_compromised', $get('stock') - $state)),
                            TextInput::make('stock_compromised')
                                ->required()
                                ->numeric()
                                ->translateLabel()
                                ->readOnly()
                                ->default(0),

                        ])->columns(6)
                        ->description(__('Data for inventory control')),

                    Section::make()->schema([
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->regex('/[0-9]{1,7}.[0-9]{2}$/')
                            ->default(0.00)
                            ->prefix('$')
                            ->translateLabel(),
                        TextInput::make('last_purchase_price')
                            ->required()
                            ->numeric()
                            ->translateLabel()
                            ->readOnly(fn($operation) => $operation != 'create')
                            ->default(0.000000),

                        TextInput::make('average_cost')
                            ->required()
                            ->numeric()
                            ->default(0.000000),
                    ])->columns(4)
                        ->description(__("Prices & Costs")),

                    // Section::make()
                    //     ->schema([
                    //         TextInput::make('stock')
                    //             ->required()
                    //             ->numeric()
                    //             ->translateLabel()
                    //             ->default(0),
                    //         TextInput::make('stock_available')
                    //             ->required()
                    //             ->numeric()
                    //             ->translateLabel()
                    //             ->gte('stock')
                    //             ->default(0)
                    //             ->live(onBlur: true)
                    //             ->afterStateUpdated(fn (Set $set, ?string $state,Get $get) => $set('stock_compromised', $get('stock') -$state)),
                    //         TextInput::make('stock_compromised')
                    //             ->required()
                    //             ->numeric()
                    //             ->translateLabel()
                    //             ->readOnly()
                    //             ->default(0),
                    //         TextInput::make('stock_min')
                    //             ->required()
                    //             ->numeric()
                    //             ->lt('stock_max')
                    //             ->translateLabel()
                    //             ->default(0),
                    //         TextInput::make('stock_max')
                    //             ->required()
                    //             ->numeric()
                    //             ->gt('stock_max')
                    //             ->translateLabel()
                    //             ->default(0),

                    //         TextInput::make('stock_reorder')
                    //             ->required()
                    //             ->numeric()
                    //             ->gte('stock_min')
                    //             ->lte('stock_max')
                    //             ->translateLabel()
                    //             ->default(0),

                    //         TextInput::make('last_purchase_price')
                    //             ->required()
                    //             ->numeric()
                    //             ->translateLabel()
                    //             ->readOnly(fn ($operation) => $operation != 'create')
                    //             ->default(0.000000),

                    //         TextInput::make('average_cost')
                    //             ->required()
                    //             ->numeric()
                    //             ->default(0.000000),

                    //     ])->columns(9),
                ])->columnSpanFull(),






            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ',')
                    ->alignEnd()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->alignEnd()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('average_cost')
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ',')
                    ->alignEnd()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->alignCenter()
                    ->translateLabel()
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.name')
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_purchase_price')
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ',')
                    ->alignEnd()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('stock_available')
                    ->numeric()
                    ->translateLabel()
                    ->alignEnd()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_compromised')
                    ->numeric()
                    ->translateLabel()
                    ->alignEnd()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stock_min')
                    ->numeric()
                    ->translateLabel()
                    ->alignEnd()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stock_max')
                    ->numeric()
                    ->translateLabel()
                    ->alignEnd()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('stock_reorder')
                    ->numeric()
                    ->translateLabel()
                    ->alignEnd()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListProductWarehouses::route('/'),
            'create' => Pages\CreateProductWarehouse::route('/create'),
            'edit' => Pages\EditProductWarehouse::route('/{record}/edit'),
        ];
    }
}
