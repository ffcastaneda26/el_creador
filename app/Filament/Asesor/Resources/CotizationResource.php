<?php
namespace App\Filament\Asesor\Resources;

use App\Filament\Asesor\Resources\CotizationResource\Pages;
use App\Models\Client;
use App\Models\Cotization;
use App\Rules\ValidImageExtension;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CotizationResource extends Resource
{
    protected static ?string $model          = Cotization::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort          = 31;

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
                            titleAttribute: 'full_name',
                        )
                        ->required()
                        ->preload()
                        ->searchable(['company_name', 'name', 'last_name', 'mother_surname', 'phone', 'email'])
                        ->getOptionLabelFromRecordUsing(fn($record) => $record->full_name)
                        ->translateLabel()
                        ->live()
                        ->afterStateHydrated(function (Set $set, Get $get) {
                            $client = $get('record')?->client;
                            if ($client) {
                                $set('require_invoice', $client->type !== 'Sin Efectos Fiscales');
                            }
                        })
                        ->afterStateUpdated(function (Set $set, $state) {
                            $clientModel = Client::find($state);
                            $set('require_invoice', $clientModel->type !== 'Sin Efectos Fiscales');
                        })
                        ->disabled(fn(Get $get) => $get('aprobada')),
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
                            ->afterStateHydrated(function (Set $set, Get $get) {
                                $client = $get('record')?->client;
                                if ($client) {
                                    $set('require_invoice', $client->type !== 'Sin Efectos Fiscales');
                                }
                            })
                            ->disabled()
                            ->default(function (Get $get) {
                                $client = $get('record')?->client;
                                if ($client) {
                                    return $client->type !== 'Sin Efectos Fiscales';
                                }
                                return true;
                            })
                            ->dehydrated(true)
                            ->afterStateUpdated(function (Set $set, Get $get) {
                                $set('tax', []);
                                CotizationResource::calculateTotals($set, $get);
                            }),
                    ])->columns(3),

                    Section::make('Impuestos')->schema([
                        Select::make('tax')
                            ->label('Impuestos')
                            ->multiple()
                            ->preload()
                            ->options(fn() => array_column(self::getTaxesConfig(), 'label', 'key'))
                            ->live()
                            ->reactive()
                            ->default([])
                            ->afterStateUpdated(fn(Set $set, Get $get) => CotizationResource::calculateTotals($set, $get))
                            ->visible(fn(Get $get) => $get('require_invoice')),
                        TextInput::make('iva')
                            ->required()
                            ->translateLabel()
                            ->inputMode('decimal')
                            ->readOnly()
                            ->disabled()
                            ->dehydrated()
                            ->visible(fn(Get $get) => $get('require_invoice')),
                        TextInput::make('retencion_isr')
                            ->required()
                            ->translateLabel()
                            ->inputMode('decimal')
                            ->readOnly()
                            ->disabled()
                            ->dehydrated()
                            ->visible(fn(Get $get) => $get('require_invoice')),
                    ])->columns(3)->visible(fn(Get $get) => $get('require_invoice')),

                    Section::make()->schema([
                        Toggle::make('aprobada')
                            ->label('¿Aprobada?')
                            ->disabled(fn($state) => $state)
                            ->dehydrated(true),
                        DatePicker::make('fecha_aprobada')
                            ->afterOrEqual('fecha')
                            ->format('Y-m-d')
                            ->requiredIf('aprobada', true)
                            ->validationMessages([
                                'required_if'    => 'Debe seleccionar fecha de aprobación si la cotización es aprobada',
                                'after_or_equal' => 'La fecha de aprobación debe ser igual o mayor a la fecha de la cotización',
                            ]),
                        DatePicker::make('fecha_entrega')
                            ->after('fecha')
                            ->format('Y-m-d'),

                        Section::make()->schema([
                            TextInput::make('subtotal')
                                ->default(0.00)
                                ->required()
                                ->translateLabel()
                                ->live(onBlur: true)
                                ->inputMode('decimal')
                                ->readOnly()
                                ->disabled()
                                ->dehydrated(),
                        TextInput::make('descuento')
                            ->default(0.00)
                            ->translateLabel()
                            ->live(onBlur: true)
                            ->inputMode('decimal')
                            ->minValue(0.00)
                            ->maxValue(fn(Get $get) => $get('subtotal') ?? null)
                            ->dehydrateStateUsing(fn($state) => $state === null ? 0 : $state)
                            ->afterStateUpdated(fn(Set $set, Get $get) => CotizationResource::calculateTotals($set, $get)),
                        TextInput::make('envio')
                            ->default(0.00)
                            ->translateLabel()
                            ->live(onBlur: true)
                            ->inputMode('decimal')
                            ->minValue(0.00)
                            ->dehydrateStateUsing(fn($state) => $state === null ? 0 : $state)
                            ->afterStateUpdated(fn(Set $set, Get $get) => CotizationResource::calculateTotals($set, $get)),
                            TextInput::make('total')
                                ->required()
                                ->disabled()
                                ->translateLabel()
                                ->readOnly()
                                ->inputMode('decimal'),
                        ])->columns(3),
                    ])->columns(3),
                ])->columns(3),

                Section::make('Partidas de la cotización')
                    ->translateLabel()
                    ->schema([
                        Repeater::make('Partidas')
                            ->relationship('details')
                            ->label('')
                            ->required()
                            ->validationMessages([
                                'required' => 'Necesitas agregar partidas',
                            ])
                            ->createItemButtonLabel('Añadir partida')
                            ->schema([
                                TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->translateLabel()
                                    ->reactive()
                                    ->default(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(callable $get, callable $set) => self::updateFormData($get, $set))
                                    ->columnSpan(1),
                                TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->translateLabel()
                                    ->reactive()
                                    ->default(0.00)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(callable $get, callable $set) => self::updateFormData($get, $set))
                                    ->afterStateHydrated(fn(callable $get, callable $set) => self::updateFormData($get, $set))
                                    ->columnSpan(1),
                                TextInput::make('total_partida')
                                    ->numeric()
                                    ->translateLabel()
                                    ->default(0.00)
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(1),
                                FileUpload::make('image')
                                    ->label('Imagen')
                                    ->image()
                                    ->disk('public')
                                    ->directory('cotizations') // Se corrige el directorio
                                    ->visibility('public')
                                    ->rules([new ValidImageExtension])
                                    ->getUploadedFileNameForStorageUsing(
                                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                            ->prepend(time() . '_'),
                                    )
                                    ->columnSpan(1),
                            ])
                            ->columns(4)
                            ->columnSpan('full')
                            ->live()
                            ->afterStateUpdated(fn(callable $set, callable $get) => self::updateFormData($get, $set))
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null),
                    ])->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.full_name')
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
                IconColumn::make('aprobada')
                    ->translateLabel()
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('fecha_aprobada')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->date('d M y')
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('details.image')
                    ->circular()
                    ->stacked()
                    ->getStateUsing(function (Cotization $record) {
                        return $record->details->pluck('image')->toArray();
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
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('envio')
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
                TextColumn::make('retencion_isr')
                    ->translateLabel()
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
                TextColumn::make('total')
                    ->formatStateUsing(fn(string $state): string => number_format($state, 2))
                    ->alignEnd(),
            ])
            ->filters([
                SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make(__('Cotization'))
                //     ->icon('heroicon-o-document-currency-dollar')
                //     ->url(fn(Cotization $record) => route('pdf-document', [$record, 'cotizacion']))
                //     ->openUrlInNewTab(),
                Tables\Actions\Action::make('view_cotization')
                    ->button()
                    ->label(__(''))
                    ->size('xs')
                    ->color('primary')
                    ->icon('heroicon-o-document')
                    ->url(fn(Cotization $record) => route('pdf-document', [$record, 'cotizacion', 'view']))
                    ->openUrlInNewTab()
                    ->tooltip(__('View quote in browser')),
                Tables\Actions\Action::make('mail_cotization')
                    ->button()
                    ->label(__(''))
                    ->size('xs')
                    ->color('warning')
                    ->icon('heroicon-o-envelope')
                    ->tooltip(__('Send quote by email'))
                    ->action(function (Cotization $record) {
                        $pdfContent = (new \App\Http\Controllers\PdfController())->cotizacion($record->id);
                        try {
                            \Illuminate\Support\Facades\Mail::to(\Illuminate\Support\Facades\Auth::user()->email)->send(new \App\Mail\DocumentEmail(ucfirst('cotizacion'), $pdfContent));
                            \Filament\Notifications\Notification::make()
                                ->title('Documento enviado')
                                ->body('La Cotización ha sido enviada por correo electrónico.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('Ocurrió un error al enviar la cotización.')
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // CotizationDetailsRelationManager::class,
            // ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCotizations::route('/'),
            'create' => Pages\CreateCotization::route('/create'),
            'edit'   => Pages\EditCotization::route('/{record}/edit'),
        ];
    }

    public static function updateFormData($get, $set)
    {
        // Partidas
        $partidas = $get("../..") ?? [];
        $subtotal = 0;
        foreach ($partidas as $partida) {
            $quantity                 = $partida['quantity'] ?? 0;
            $price                    = $partida['price'] ?? 0;
            $total                    = round($quantity * $price, 2);
            $partida['total_partida'] = $total;
            $subtotal += $total;
        }

        $quantity = $get('quantity');

        $price = $get('price');
        $total = round($quantity * $price, 2);
        $set('total_partida', $total);
        $set('total', $total);
        $set("../../subtotal", round($subtotal, 2));
        self::calculateTotals($set, $get);

    }
    public static function calculateTotals(Set $set, Get $get)
    {
        $subtotal       = round(floatval($get('subtotal')), 2);
        $requireInvoice = $get('require_invoice');
        $descuento      = round(floatval($get('descuento')), 2);
        $envio          = round(floatval($get('envio')), 2);

        // El descuento aplica sobre el subtotal y afecta el IVA/retenciones. El envío se suma antes de calcular impuestos.
        $base        = round($subtotal - $descuento + $envio, 2);
        $base        = max($base, 0); // Evita bases negativas.
        $iva         = 0.00;
        $retenciones = 0.00;

        if ($requireInvoice) {
            $taxSelections = $get('tax') ?? [];
            $taxConfig     = collect(self::getTaxesConfig())->keyBy('key');
            foreach ($taxSelections as $taxKey) {
                if (! $taxConfig->has($taxKey)) {
                    continue;
                }
                $tax    = $taxConfig[$taxKey];
                $amount = round($base * ($tax['percent'] / 100), 2);
                if ($tax['type'] === 'add') {
                    $iva += $amount;
                }
                if ($tax['type'] === 'retention') {
                    $retenciones += $amount;
                }
            }
        } else {
            $set('tax', []);
        }

        $set('iva', round($iva, 2));
        $set('retencion_isr', round($retenciones, 2));
        $total = round($base + $iva - $retenciones, 2);
        $set('total', $total);
    }

    public static function getTaxesConfig(): array
    {
        return [
            ['key' => 'iva_16', 'label' => 'IVA 16%', 'percent' => 16.00, 'type' => 'add'],
            ['key' => 'ret_iva_1_25', 'label' => 'Ret. IVA 1.25%', 'percent' => 1.25, 'type' => 'retention'],
        ];
    }
}
