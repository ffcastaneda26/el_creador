<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Country;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Movement;
use Filament\Forms\Form;
use App\Models\Warehouse;
use Filament\Tables\Table;
use App\Models\ProductWarehouse;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductWarehouseResource\Pages;
use App\Filament\Resources\ProductWarehouseResource\RelationManagers;
use App\Models\Product;

class ProductWarehouseResource extends Resource
{
    protected static ?string $model = ProductWarehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 22;
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('Administrador') && Warehouse::hasRecords() &&  Product::hasRecords() ;
    }

    // public static function getNavigationParentItem(): ?string
    // {
    //     return __('Warehouses');
    // }
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
                                    ->required(fn() => Warehouse::count() != 1)
                                    ->visible(fn() => Warehouse::count() != 1),
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->translateLabel()
                                    ->required()
                                    ->rules([
                                        fn(Get $get, Set $set, string $operation): Closure => function (string $attribute, $value, Closure $fail) use ($get, $set, $operation) {
                                            if ($operation == 'create') {
                                                if (Warehouse::count() == 1) {
                                                    $set('warehouse_id', Warehouse::first()->id);
                                                }
                                                $exists = ProductWarehouse::where('product_id', $get('product_id'))
                                                    ->where('warehouse_id', $get('warehouse_id'))
                                                    ->exists();
                                                if ($exists) {
                                                    $fail(__('The product already exists in this warehouse'));
                                                }
                                            }
                                        },
                                    ]),
                                TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->regex('/[0-9]{1,7}.[0-9]{2}$/')
                                    ->default('0.00')
                                    ->prefix('$')
                                    ->translateLabel(),
                                Toggle::make('active')
                                    ->default(true),
                            ])->columns(3),
                    ])->columnSpanFull(),
                Group::make()->schema([
                    Section::make()
                        ->schema([
                            TextInput::make('stock')
                                ->required()
                                ->translateLabel()
                                ->regex('/[0-9]$/')
                                ->default(0),
                            TextInput::make('stock_min')
                                ->numeric()
                                ->default(0)
                                ->translateLabel(),
                            TextInput::make('stock_max')
                                ->gte('stock_min')
                                ->numeric()
                                ->default(0)
                                ->translateLabel(),
                            TextInput::make('stock_reorder')
                                ->gte('stock_min')
                                ->lte('stock_max')
                                ->default(0)
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

                    // Section::make()->schema([
                    //     TextInput::make('price')
                    //         ->required()
                    //         ->numeric()
                    //         ->regex('/[0-9]{1,7}.[0-9]{2}$/')
                    //         ->default(0.00)
                    //         ->prefix('$')
                    //         ->translateLabel(),
                    //     TextInput::make('last_purchase_price')
                    //         ->required()
                    //         ->numeric()
                    //         ->translateLabel()
                    //         ->readOnly(fn($operation) => $operation != 'create')
                    //         ->default(0.00),

                    //     TextInput::make('average_cost')
                    //         ->required()
                    //         ->numeric()
                    //         ->translateLabel()
                    //         ->default(0.00)
                    //         ->readOnly(fn($operation) => $operation != 'create')
                    // ])->columns(4)
                    // ->description(__("Prices & Costs")),
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
                    ->numeric(decimalPlaces: 4, decimalSeparator: '.', thousandsSeparator: ',')
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
                Tables\Actions\DeleteAction::make()
                    ->disabled(function (ProductWarehouse $record) {
                        return Movement::where('warehouse_id', $record->warehouse_id)
                            ->where('product_id', $record->product_id)
                            ->count();
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
            'index' => Pages\ListProductWarehouses::route('/'),
            'create' => Pages\CreateProductWarehouse::route('/create'),
            'edit' => Pages\EditProductWarehouse::route('/{record}/edit'),
        ];
    }


}
