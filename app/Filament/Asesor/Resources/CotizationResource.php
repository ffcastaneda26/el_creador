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

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 31;

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
                        ->searchable(['name', 'phone', 'email'])
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
                            ->live(onBlur: true)
                            ->reactive()
                            ->label('¿Va a Requerir Factura?')
                            ->afterStateUpdated(function (callable $get, Set $set, ?string $state) {
                                $subtotal = floatval($get('subtotal'));
                                $descuento = floatval($get('descuento'));
                                $envio = floatval($get('envio'));
                                $iva = 00.00;
                                $tax = $get('tax');
                                $retencion_isr = 0;
                                if ($tax) {
                                    $iva = round(($subtotal - $descuento + $envio) * 0.16, 2);
                                    $percentage_retencion =  env('PERCENTAGE_RETENCION_ISR', 1.25);
                                    $base_retencion = round($subtotal - $envio);
                                    $retencion_isr = round($base_retencion * ($percentage_retencion / 100), 2);
                                }
                                $set('iva', $iva);
                                $set('retencion_isr', $retencion_isr);
                                $total = round($subtotal + $iva - $descuento + $envio - $retencion_isr, 2);
                                $set('total', $total);
                            })
                            ->columnSpan(2),
                        Section::make()->schema([
                            TextInput::make('subtotal')
                                ->default(0.00)
                                ->required()
                                ->translateLabel()
                                ->live(onBlur: true)
                                ->inputMode('decimal')
                                ->afterStateUpdated(function (callable $get, Set $set, ?string $state) {
                                    $descuento = floatval($get('descuento'));
                                    $envio = floatval($get('envio'));
                                    $iva = 0.00;
                                    $retencion_isr = 0.00;
                                    $tax = $get('tax');
                                    if ($tax) {
                                        $iva = round(($state + $envio - $descuento) * 0.16, 2);
                                        $percentage_retencion =  env('PERCENTAGE_RETENCION_ISR', 1.25);
                                        $base_retencion = round($state - $envio);
                                        $retencion_isr = round($base_retencion * ($percentage_retencion / 100), 2);
                                    }
                                    $set('iva', $iva);
                                    $set('retencion_isr', floatval($retencion_isr));
                                    $total = round($state + $iva - $descuento + $envio - $retencion_isr, 2);
                                    $set('total', $total);
                                }),
                            TextInput::make('descuento')
                                ->default(0.00)
                                ->translateLabel()
                                ->live(onBlur: true)
                                ->inputMode('decimal')
                                ->afterStateUpdated(function (callable $get, Set $set, ?string $state) {
                                    $subtotal = floatval($get('subtotal'));
                                    $envio = floatval($get('envio'));
                                    $iva = 0.00;
                                    $retencion_isr = 0.00;
                                    $tax = $get('tax');
                                    if ($tax) {
                                        $iva = round(($subtotal + $envio) * 0.16, 2);
                                        $percentage_retencion =  env('PERCENTAGE_RETENCION_ISR', 1.25);
                                        $base_retencion = round($subtotal - $envio);
                                        $retencion_isr = round($base_retencion * ($percentage_retencion / 100), 2);
                                    }
                                    $set('iva', $iva);
                                    $set('retencion_isr', floatval($retencion_isr));
                                    $total = round($subtotal + $iva - $state + $envio - $retencion_isr, 2);
                                    $set('total', $total);
                                }),
                            TextInput::make('envio')
                                ->default(0.00)
                                ->translateLabel()
                                ->live(onBlur: true)
                                ->inputMode('decimal')
                                ->afterStateUpdated(function (callable $get, Set $set, ?string $state) {
                                    $subtotal =floatval($get('subtotal'));
                                    $descuento = floatval($get('descuento'));
                                    $tax = $get('tax');
                                    $iva = 0.00;
                                    $retencion_isr = 0.00;
                                    if ($tax) {
                                        $iva = round(($state + $subtotal) * 0.16, 2);
                                        $percentage_retencion =  env('PERCENTAGE_RETENCION_ISR', 1.25);
                                        $base_retencion = round($subtotal - $state);
                                        $retencion_isr = round($base_retencion * ($percentage_retencion / 100), 2);
                                    }
                                    $set('iva', $iva);
                                    $set('retencion_isr', $retencion_isr);

                                    $total = round($subtotal +  $iva - $descuento + $state, 2);
                                    $set('total', $total);
                                }),
                            TextInput::make('iva')
                                ->required()
                                ->translateLabel()
                                ->inputMode('decimal')
                                ->disabled(),

                        ])->columns(4),
                        TextInput::make('retencion_isr')
                            ->required()
                            ->translateLabel()
                            ->inputMode('decimal')
                            ->disabled(),
                        TextInput::make('total')
                            ->required()
                            ->disabled()
                            ->translateLabel()
                            ->inputMode('decimal'),
                    ])->columns(2),

                    Section::make()->schema([
                        Toggle::make('aprobada')->label('¿Aprobada?'),
                        DatePicker::make('fecha_aprobada')
                            ->afterOrEqual('fecha')
                            ->format('Y-m-d')
                            ->requiredIf('aprobada', true)
                            ->validationMessages([
                                'required_if' => 'Debe seleccionar fecha de aprobación si la cotización es aprobada',
                                'after_or_equal' => 'La fecha de aproBación debe ser igual o mayor a la fecha de la cotización',

                            ]),

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
                TextColumn::make('fecha_aprobada')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->date('d M y')
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('images.image')->circular()->stacked()->translateLabel(),
                TextColumn::make('subtotal')
                    ->formatStateUsing(fn(string $state): string => number_format($state))
                    ->alignEnd(),
                TextColumn::make('iva')
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
                TextColumn::make('descuento')
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('envio')
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
                TextColumn::make('retencion_isr')
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
                TextColumn::make('total')
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
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
                    ->url(fn(Cotization $record) => route('pdf-document', [$record, 'cotizacion']))
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

    private static function calculateTax() {}
}
