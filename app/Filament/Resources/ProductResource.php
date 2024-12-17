<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\Markdown;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;


    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';


    protected static ?int $navigationSort = 22;

    public static function getNavigationGroup(): string
    {
        return __('Catalogs');
    }
    public static function getModelLabel(): string
    {
        return __('Product');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Products');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->translateLabel()
                    ->columnSpan(2),
                TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->translateLabel(),
                Select::make('unit_id')
                    ->required()
                    ->relationship(name: 'unit', titleAttribute: 'name')
                    ->searchable(condition: ['name', 'symbol'])
                    ->label(__('Unit of Measurement'))
                    ->preload(),
                    Section::make()->schema([
                        MarkdownEditor::make('description')
                            ->translateLabel()
                            ->columnSpan(2),
                        FileUpload::make('image')
                            ->translateLabel()
                            ->directory('products')
                            ->preserveFilenames()
                            ->columnSpan(2),
                    ])->columns(4),


            ])->columns(5);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                TextColumn::make('unit.name')
                    ->searchable()
                    ->sortable()
                    ->label(__('Unit of Measurement')),
                TextColumn::make('price')
                    ->translateLabel()
                    ->alignment(Alignment::End)
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.' , thousandsSeparator: ',')
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
