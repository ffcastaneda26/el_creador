<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Client;
use App\Models\Order;
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
                            modifyQueryUsing: fn($query) => $query->whereHas('pending_orders'),
                        )
                        ->required()
                        ->reactive()
                        ->preload()
                        ->translateLabel()
                        ->searchable(['name', 'phone', 'email'])
                        ->afterStateUpdated(fn(callable $set) => $set('order_id', null)),

                    Forms\Components\Select::make('order_id')
                        ->translateLabel()
                        ->required()
                        ->reactive()
                        ->options(function (callable $get) {
                            $client = Client::find($get('client_id'));
                            if (!$client) {
                                return;
                            }
                            // return $client->pending_orders->pluck('date', 'id');
                            return $client->pending_orders->mapWithKeys(function ($order) {
                                return [$order->id => $order->date->format('Y-m-d')];
                            });
                        }),


                    Forms\Components\TextInput::make('amount')
                        ->required()
                        ->numeric()
                        ->prefix('$')
                        ->translateLabel()
                        ->formatStateUsing(fn($state) => number_format($state, 2, '.', ''))
                        ->default(0.00)
                        ->dehydrateStateUsing(fn($state) => round(floatval($state), 2))
                        ->rules(['numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'])
                        ->maxValue(fn($get) => Order::find($get('order_id'))?->pending_balance ?? 0),
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
                Tables\Columns\TextColumn::make('client.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order.date')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->translateLabel()
                    ->searchable()
                    ->alignEnd()
                    ->sortable()
                    ->money('MXP', true),
                // ->formatStateUsing(fn(string $state): string => number_format($state, 2)),
                Tables\Columns\TextColumn::make('reference')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('card_number')
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('voucher_file')
                    ->translateLabel()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->translateLabel()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->translateLabel()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                tables\Filters\SelectFilter::make('client')
                    ->relationship('client', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->preload()
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
