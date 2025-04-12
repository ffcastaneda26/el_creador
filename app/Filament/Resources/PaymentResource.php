<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 33;

    public static function getNavigationGroup(): string
    {
        return __('Sales');
    }
    public static function getModelLabel(): string
    {
        return __("Payment");
    }


    public static function getPluralLabel(): ?string
    {
        return __("Payments");
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Select::make('client_id')
                        ->relationship(
                            name: 'client',
                            titleAttribute: 'name',
                        )
                        ->required()
                        ->preload()
                        ->searchable(['name', 'phone', 'email'])
                        ->translateLabel(),
                    // TODO: Filtrar por las órdenes de compra del cliente que aún tengan saldo pendiente

                    Forms\Components\Select::make('order_id')
                        ->relationship(
                            name: 'order',
                            titleAttribute: 'id',
                        )
                        ->required()
                        ->preload()
                        ->translateLabel(),
                    // Forms\Components\TextInput::make('amount')
                    //     ->default(0.00)
                    //     ->required()
                    //     ->translateLabel()
                    //     ->live(onBlur: true)
                    //     ->inputMode('decimal')
                    //     ->numeric(),
                    Forms\Components\TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->prefix('$')
                        ->translateLabel()
                        ->formatStateUsing(fn($state) => number_format($state, 2, '.', ''))
                        ->default(0.00)
                        ->dehydrateStateUsing(fn($state) => round(floatval($state), 2))
                        ->rules(['numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/']),
                    Forms\Components\TextInput::make('reference')
                        ->maxLength(50)
                        ->translateLabel(),

                ])->columns(2),
                Forms\Components\Group::make()->schema([
                    Forms\Components\DatePicker::make('date')
                        ->required()
                        ->default(now())
                        ->translateLabel()
                        ->format('Y-m-d'),
                    Forms\Components\Select::make('payment_method_id')
                        ->relationship(
                            name: 'paymentMethod',
                            titleAttribute: 'name',
                        )
                        ->required()
                        ->preload()
                        ->live(onBlur: true)
                        ->translateLabel(),
                    Forms\Components\TextInput::make('reference_number')
                        ->maxLength(20)
                        ->label(fn($get) => ($get('payment_method_id') == 5 || $get('payment_method_id') == 6) ? __('Card number') : __('Check number'))
                        ->visible(fn($get) => $get('payment_method_id') == 5 || $get('payment_method_id') == 6 || $get('payment_method_id') == 4),


                ])->columns(2),

                Forms\Components\FileUpload::make('voucher_image')
                    ->translateLabel()
                    ->directory('/sells/payments/vouchers')
                    ->preserveFilenames()
                    ->columnSpanFull()



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('card_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('voucher_file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
