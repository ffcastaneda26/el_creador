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
use App\Models\Client;
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
                            titleAttribute: 'full_name', // Puedes mantener esto, ya que el método lo sobrescribe
                        )
                        ->required()
                        ->preload()
                        ->searchable(['company_name', 'name', 'last_name', 'mother_surname', 'phone', 'email'])
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->full_name) // Añade esta línea
                        ->translateLabel()
                        ->live() // Añade esta línea
                        ->afterStateUpdated(function (Set $set, $state) {
                            $clientModel = Client::find($state);
                            $set('require_invoice', $clientModel->type !== 'Sin Efectos Fiscales');
                        }),
                    MarkdownEditor::make('description')
                        ->required()
                        ->translateLabel()
                        ->columnSpan(2),
                ]),
                Group::make()->schema([
                    Section::make()->schema([
                        DatePicker::make('fecha')
                            ->required()
                            ->default(now())
                            ->format('Y-m-d'),
                        DatePicker::make('vigencia')
                            ->required()
                            ->format('Y-m-d')
                            ->after('fecha'),
                        Toggle::make('require_invoice')
                            ->translateLabel()
                            ->live(onBlur: true)
                            ->reactive()
                            ->disabled() // Lo hacemos de solo lectura deshabilitándolo.
                            ->dehydrated(true) // Asegura que el valor se guarde aunque esté deshabilitado.
                            ->afterStateHydrated(function (Set $set, Get $get) {
                                // Obtenemos el registro actual si existe.
                                $record = $get('record');
                                if ($record && $record->client) {
                                    $client = $record->client;
                                    // Configuramos el estado basado en el tipo de cliente.
                                    $set('require_invoice', $client->type !== 'Sin Efectos Fiscales');
                                }
                            })
                            ->afterStateUpdated(fn(Set $set, Get $get) => CotizationResource::calculateTotals($set, $get)),
                    ])->columns(3),

                    Section::make()->schema([

                        Section::make()->schema([
                            TextInput::make('subtotal')
                                ->default(0.00)
                                ->required()
                                ->translateLabel()
                                ->live(onBlur: true)
                                ->inputMode('decimal')
                                ->afterStateUpdated(fn(Set $set, Get $get) => CotizationResource::calculateTotals($set, $get)),
                            TextInput::make('descuento')
                                ->default(0.00)
                                ->translateLabel()
                                ->live(onBlur: true)
                                ->inputMode('decimal')
                                ->afterStateUpdated(fn(Set $set, Get $get) => CotizationResource::calculateTotals($set, $get)),
                            TextInput::make('envio')
                                ->default(0.00)
                                ->translateLabel()
                                ->live(onBlur: true)
                                ->inputMode('decimal')
                                ->afterStateUpdated(fn(Set $set, Get $get) => CotizationResource::calculateTotals($set, $get)),

                        ])->columns(3),

                        // Section::make()->schema([
                        //     TextInput::make('base_retencion')
                        //         ->label('Base Retención')
                        //         ->inputMode('decimal')
                        //         ->disabled(),
                        //     TextInput::make('percentage_retencion')
                        //         ->label('% Retención')
                        //         ->inputMode('decimal')
                        //         ->disabled(),
                        //     TextInput::make('percentage_iva')
                        //         ->label('% IVA')
                        //         ->inputMode('decimal')
                        //         ->disabled(),
                        // ])
                        // ->columns(3)
                        // ->hidden(),
                        TextInput::make('iva')
                            ->required()
                            ->translateLabel()
                            ->inputMode('decimal')
                            ->disabled(),
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
                    ])->columns(3),

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
                ])->columns(3),
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
                // ImageColumn::make('images.image')->circular()->stacked()->translateLabel(),
                ImageColumn::make('images.image')
                    ->circular()
                    ->stacked()
                    ->getStateUsing(function (Cotization $record) {
                        return $record->images->pluck('image')->toArray();
                    })
                    ->translateLabel(),
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

    private static function calculateTax()
    {
    }

    public static function calculateTotals(Set $set, Get $get)
    {
        $require_invoice = $get('require_invoice');
        $subtotal = round(floatval($get('subtotal')), 2);
        $descuento = round(floatval($get('descuento')), 2);
        $envio = round(floatval($get('envio')), 2);
        $iva = 00.00;
        $retencion_isr = 00.00;

        if ($require_invoice) {
            $percentage_iva = round(env('PERCENTAGE_IVA', 16) / 100, 2);
            $percentage_retencion = env('PERCENTAGE_RETENCION_ISR', 1.25);
            $base_retencion = round($subtotal - $descuento + $envio, 2);
            $iva = round($base_retencion * $percentage_iva, 2);
            $retencion_isr = round($base_retencion * ($percentage_retencion / 100), 2);
        }
        $set('iva', $iva);
        $set('retencion_isr', $retencion_isr);
        $total = round($subtotal + $iva - $descuento + $envio - $retencion_isr, 2);
        $set('total', $total);

    }
}
