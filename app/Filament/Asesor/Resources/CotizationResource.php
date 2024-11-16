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
use App\Filament\Asesor\Resources\CotizationResource\RelationManagers\ImagesRelationManager;
use Filament\Forms\Get;
use Filament\Tables\Columns\ImageColumn;

class CotizationResource extends Resource
{
    protected static ?string $model = Cotization::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';


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
                    Section::make()->schema([
                            Select::make('tax')
                                ->options([
                                    true => __('Yes'),
                                    false => 'No',
                                    ])
                                ->live(onBlur:true)
                                ->reactive()
                                ->label('¿Incluir Iva?')
                                ->afterStateUpdated(function (callable $get,Set $set,?string $state) {
                                        $subtotal= $get('subtotal');
                                        $descuento= $get('descuento');
                                        $envio = $get('envio');
                                        $iva= 00.00;
                                        $tax = $get('tax');
                                        if($tax){
                                            $iva= round($subtotal*0.16,2);
                                        }
                                        $set('iva',$iva);
                                        $total = round($subtotal + $iva - $descuento +$envio,2);
                                        $set('total',$total);

                                    })
                                ->columnSpan(2),
                            Section::make()->schema([
                                TextInput::make('subtotal')
                                    ->default(0.00)
                                    ->required()
                                    ->translateLabel()
                                    ->live(onBlur:true)
                                    ->inputMode('decimal')
                                    ->afterStateUpdated(function (callable $get,Set $set,?string $state) {
                                        $descuento= $get('descuento');
                                        $envio= $get('envio');
                                        $iva= 0.00;
                                        $tax = $get('tax');
                                        if($tax){
                                            $iva= round($state*0.16,2);
                                        }
                                        $set('iva',$iva);
                                        $total = round($state + $iva - $descuento + $envio,2);
                                        $set('total',$total);
                                }),
                                TextInput::make('descuento')
                                    ->default(0.00)
                                    ->translateLabel()
                                    ->live(onBlur:true)
                                    ->inputMode('decimal')
                                    ->afterStateUpdated(function (callable $get,Set $set,?string $state) {
                                        $subtotal= $get('subtotal');
                                        $envio = $get('envio');
                                        $iva = $get('iva');
                                        $total = round($subtotal +  $iva - $state + $envio,2);
                                        $set('total',$total);
                                }),
                                TextInput::make('envio')
                                    ->default(0.00)
                                    ->translateLabel()
                                    ->live(onBlur:true)
                                    ->inputMode('decimal')
                                    ->afterStateUpdated(function (callable $get,Set $set,?string $state) {
                                        $subtotal= $get('subtotal');
                                        $descuento = $get('descuento');
                                        $iva = $get('iva');
                                        $total = round($subtotal +  $iva - $descuento + $state,2);
                                        $set('total',$total);
                                }),
                            ])->columns(3),

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

                        ])->columns(2),

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
                        ->date('d M y'),

                TextColumn::make('vigencia')
                        ->translateLabel()
                        ->searchable()
                        ->sortable()
                        ->date('d M y')
                        ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('aprobada')->translateLabel()->boolean(),
                ImageColumn::make('images.image')->circular()->stacked()->translateLabel(),
                TextColumn::make('subtotal')
                    ->formatStateUsing(fn (string $state): string => number_format($state))
                    ->alignEnd(),
                TextColumn::make('iva')
                    ->formatStateUsing(fn (string $state): string => number_format($state,2))
                    ->alignEnd(),
                TextColumn::make('descuento')
                    ->formatStateUsing(fn (string $state): string => number_format($state,2))
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('envio')
                    ->formatStateUsing(fn (string $state): string => number_format($state,2))
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total')
                        ->formatStateUsing(fn (string $state): string => number_format($state,2))
                        ->alignEnd()
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
                Tables\Actions\Action::make(__('Cotization'))
                    ->icon('heroicon-o-document-currency-dollar')
                    ->url(fn (Cotization $record) => route('pdf-document',[ $record,'cotizacion']))
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
