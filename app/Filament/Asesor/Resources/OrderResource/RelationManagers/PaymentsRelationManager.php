<?php

namespace App\Filament\Asesor\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Pagos';

    protected static ?string $modelLabel = 'pago';

    protected static ?string $pluralModelLabel = 'pagos';

    protected static ?string $icon = 'heroicon-o-banknotes';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('date')
                ->label('Fecha')
                ->required()
                ->default(now())
                ->maxDate(now()),

            Forms\Components\Select::make('payment_method_id')
                ->label('Forma de pago')
                ->relationship('paymentMethod', 'name')
                ->required()
                ->preload()
                ->native(false),

            Forms\Components\TextInput::make('amount')
                ->label('Importe')
                ->required()
                ->numeric()
                ->minValue(0.01)
                ->prefix('$')
                // No se puede cobrar mas de lo que resta. Al editar un pago su propio
                // importe ya esta descontado del saldo, asi que se vuelve a sumar.
                ->maxValue(function (string $operation, ?Model $record): float {
                    $pending = (float) $this->getOwnerRecord()->pending_balance;

                    return $operation === 'edit'
                        ? $pending + (float) $record?->amount
                        : $pending;
                })
                ->helperText(fn (): string => 'Saldo pendiente: $'
                    . number_format((float) $this->getOwnerRecord()->pending_balance, 2)),

            Forms\Components\TextInput::make('reference_number')
                ->label('Referencia')
                ->maxLength(20),

            Forms\Components\Textarea::make('notes')
                ->label('Notas')
                ->maxLength(500)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference_number')
            ->defaultSort('date')
            ->columns([
                Tables\Columns\TextColumn::make('date')->label('Fecha')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')->label('Forma de pago'),
                Tables\Columns\TextColumn::make('amount')->label('Importe')->money('MXN')->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Cobrado')->money('MXN')),
                Tables\Columns\TextColumn::make('reference_number')->label('Referencia')->toggleable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Registrar pago')
                    // El pedido ya define de que cliente es: se copia para no pedirlo otra vez
                    // y para que no pueda quedar en blanco (la columna es obligatoria).
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['client_id'] = $this->getOwnerRecord()->client_id;

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
