<?php

namespace App\Filament\Asesor\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Cotization;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use App\Helpers\ManagementCotization;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Asesor\Resources\CotizationResource\Pages;
use App\Filament\Asesor\Resources\CotizationResource\RelationManagers;
use App\Filament\Asesor\Resources\CotizationResource\RelationManagers\CommentsRelationManager;
use App\Filament\Asesor\Resources\CotizationResource\RelationManagers\ImagesRelationManager;
use Filament\Tables\Columns\ImageColumn;

class CotizationResource extends Resource
{
    protected static ?string $model = Cotization::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';


    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): string
    {
        return __('Sales');
    }

    public static function getModelLabel(): string
    {
        return __('Cotization');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Cotizations');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Select::make('client_id')
                        ->relationship(
                                name: 'client',
                                titleAttribute: 'name',
                            )
                        ->required()
                        ->preload()
                        ->searchable(['name', 'phone','email'])
                        ->translateLabel(),

                        MarkdownEditor::make('description')
                            ->required()
                            ->translateLabel()
                            ->columnSpan(2),
                    ]),
                    Group::make()->schema([
                        DatePicker::make('fecha')
                            ->required()
                            ->default(now())
                            ->format('Y-m-d'),
                        DatePicker::make('vigencia')
                            ->required()
                            ->format('Y-m-d')
                            ->after('fecha'),
                        TextInput::make('subtotal')
                            ->default(0.00)
                        	->required()
                            ->translateLabel()
                            ->live(onBlur:true)
                            ->inlinelabel()
                            ->inputMode('decimal')
                            ->afterStateUpdated(function (callable $get,Set $set,?string $state) {
                                $descuento= $get('descuento');
                                $iva= round($state*0.16,2);
                                $set('iva',$iva);
                                $total = round($state + $iva + $descuento,2);
                                $set('total',$total);
                            }),
                        TextInput::make('descuento')
                        	->default(0.00)
                            ->translateLabel()
                            ->live(onBlur:true)
                            ->inputMode('decimal')
                            ->inlinelabel()
                            ->afterStateUpdated(function (callable $get,Set $set,?string $state) {
                                $subtotal= $get('subtotal');
                                $iva = $get('iva');
                                $total = round($state + $iva + $subtotal,2);
                                $set('total',$total);
                            }),
                        TextInput::make('iva')
                        	->required()
                            ->translateLabel()
                            ->inputMode('decimal')
                            ->disabled()
                            ->inlinelabel(),

                        TextInput::make('total')
                        	->required()
                            ->disabled()
                            ->translateLabel()
                            ->inputMode('decimal')
                            ->inlinelabel(),
                        Section::make()->schema([
                            Toggle::make('aprobada'),
                            DatePicker::make('fecha_aprobada')
                                ->requiredIfAccepted('aprobada')
                                ->afterOrEqual('fecha')
                                ->format('Y-m-d'),
                            DatePicker::make('fecha_entrega')
                                ->after('fecha')
                                ->format('Y-m-d'),

                        ])->columns(3)
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('client.name')
                        ->searchable()
                        ->sortable()
                        ->label(__('Client')),
                TextColumn::make('fecha')
                        ->translateLabel()
                        ->searchable()
                        ->sortable()
                        ->date('D d M y'),
                TextColumn::make('vigencia')
                        ->translateLabel()
                        ->searchable()
                        ->sortable()
                        ->date('D d M y'),

                IconColumn::make('aprobada')->translateLabel()->boolean(),
                ImageColumn::make('images.image')->circular()->stacked()
            ])
            ->filters([
                SelectFilter::make('client')
                ->relationship('client', 'name')
                ->translateLabel()
                ->searchable()
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
            // ImagesRelationManager::class,
           ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCotizations::route('/'),
            'create' => Pages\CreateCotization::route('/create'),
            'edit' => Pages\EditCotization::route('/{record}/edit'),
        ];
    }
}
