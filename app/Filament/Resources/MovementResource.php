<?php

namespace App\Filament\Resources;

use Closure;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Movement;
use Filament\Forms\Form;
use App\Models\Warehouse;
use Filament\Tables\Table;
use App\Models\KeyMovement;
use App\Models\ProductWarehouse;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;

use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\MovementResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MovementResource\RelationManagers;

class MovementResource extends Resource
{
    protected static ?string $model = Movement::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('Administrador');
    }

    protected static ?int $navigationSort = 60;
    public static function getNavigationLabel(): string
    {
        return __('Warehouse Movements');
    }

    public static function getModelLabel(): string
    {
        return __('Warehouse Movement');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Warehouse Movements');
    }
    public static function getNavigationGroup(): string
    {
        return __('Inventory');
    }

    public static function form(Form $form): Form
    {
 
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('warehouse_id')
                            ->relationship('warehouse', 'name')
                            ->translateLabel()
                            ->rules([
                                fn(Get $get, string $operation): Closure => function (string $attribute, $value, Closure $fail) use ($get, $operation) {
                                    if ($operation == 'create') {
                                        $exists = ProductWarehouse::where('product_id', $get('product_id'))
                                            ->where('warehouse_id', $get('warehouse_id'))
                                            ->exists();
                                        if (!$exists) {
                                            $fail(__('The product does not exist in this warehouse'));
                                        }
                                    }
                                },
                            ])
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(callable $set) => $set('product_id', null)),
                        Select::make('product_id')
                            ->required()
                            ->translateLabel()
                            ->relationship(name: 'product', titleAttribute: 'name')
                            ->reactive()
                            ->disabled(function (callable $get): bool {
                                return $get('warehouse_id') ? false : true;
                            }),
                        Select::make('key_movement_id')
                            ->required()
                            ->translateLabel()
                            ->reactive()
                            ->relationship(name: 'key_movement', titleAttribute: 'name')
                            ->afterStateUpdated(
                                function (callable $get, string $operation, Set $set, ?int $state) {
                                    if ($state) {
                                        $set('cost', null);
                                        $key_movement = KeyMovement::findOrFail($state);
                                        if (!$key_movement->require_cost) {
                                            $product_cost = ProductWarehouse::where('warehouse_id', $get('warehouse_id'))
                                                                            ->where('product_id', $get('product_id'))
                                                                            ->first();
                                            if ($product_cost) {
                                                $set('cost', $product_cost->average_cost);
                                            }
                                        }
                                    }
                                }
                            )
                            ->disabled(function (callable $get): bool {
                                return $get('product_id') ? false : true;
                            }),

                    ])->columns(3),
                Fieldset::make(__('Details'))
                    ->schema([
                        DatePicker::make('date')
                            ->required()
                            ->translateLabel()
                            ->before(now())
                            ->disabled(function (callable $get): bool {
                                return $get('key_movement_id') ? false : true;
                            }),
                        TextInput::make('quantity')
                            ->required()
                            ->translateLabel()
                            ->minValue(1)
                            ->numeric()
                            ->maxValue(function (callable $get): int {
                                if ($get('key_movement_id')) {
                                    $key_movement = KeyMovement::findOrFail($get('key_movement_id'));
                                    if ($key_movement->type == 'O') {
                                        $product_validate = ProductWarehouse::where('warehouse_id', $get('warehouse_id'))
                                            ->where('product_id', $get('product_id'))
                                            ->first();
                                        return $product_validate ? $product_validate->stock_available : 9999;
                                    }
                                }
                                return 9999;
                            })
                            ->disabled(function (callable $get): bool {
                                return $get('key_movement_id') ? false : true;
                            }),
                        TextInput::make('cost')
                            ->translateLabel()
                            ->required(function (callable $get): bool {
                                $key_movement = null;
                                if ($get('key_movement_id')) {
                                    $key_movement = KeyMovement::findOrFail($get('key_movement_id'));
                                }
                                return $key_movement && $key_movement->type == 'I';
                            })->disabled(function (callable $get): bool {
                                $key_movement = null;
                                if ($get('key_movement_id')) {
                                    $key_movement = KeyMovement::findOrFail($get('key_movement_id'));
                                    return $key_movement && $key_movement->type == 'O';
                                }
                                return $get('key_movement_id') ? false : true;
                            }),
                        TextInput::make('reference')
                            ->translateLabel()
                            ->maxLength(100),
                    ])->disabled(function (callable $get): bool {
                        return $get('key_movement_id') ? false : true;
                    })->columns(5),
                Fieldset::make(__('Notes and Receipt'))
                    ->schema([
                        MarkdownEditor::make('notes')
                            ->translateLabel(),
                        FileUpload::make('voucher_image')
                            ->translateLabel()
                            ->directory('/inventory/movements/vouchers')
                            ->preserveFilenames(),
                    ])->visible(function (callable $get): bool {
                        return $get('key_movement_id') ? true : false;
                    })->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('warehouse.name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('date')
                    ->searchable()
                    ->sortable()
                    ->dateTime('d-m-Y')
                    ->translateLabel(),
                TextColumn::make('key_movement.name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('quantity')
                    ->searchable()
                    ->sortable()
                    ->translateLabel()
                    ->alignment(Alignment::End)
                    ->summarize(Sum::make())
                    ->numeric(decimalPlaces: 0, decimalSeparator: '.', thousandsSeparator: ','),
                TextColumn::make('cost')
                    ->translateLabel()
                    ->alignment(Alignment::End)
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ','),
                TextColumn::make('amount')
                    ->translateLabel()
                    ->alignment(Alignment::End)
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ','),
             ])
            ->filters([
                SelectFilter::make('Key Movement')
                    ->relationship('key_movement', 'name')
                    ->translateLabel()
                    ->preload(),
                SelectFilter::make('Product')
                    ->relationship('product', 'name')
                    ->translateLabel()
                    ->preload()
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
            'index' => Pages\ListMovements::route('/'),
            'create' => Pages\CreateMovement::route('/create'),
            'edit' => Pages\EditMovement::route('/{record}/edit'),
        ];
    }

    private static function getWareHouseProduct($warehouse_id, $product_id): ProductWarehouse
    {
        return ProductWarehouse::where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->first();
    }
    private static function getKeyMovements(): array
    {
        return KeyMovement::where('used_to', 'I')
            ->pluck('name', 'id')->all();
    }
}
