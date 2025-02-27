<?php

namespace App\Filament\Resources\PurchaseResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Purchase;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\Enums\StatusPurchaseEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // TODO: Validar para que no se duplique en la misma solicitud
                Forms\Components\Select::make('product_id')
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->preload()
                    ->searchable(['name', 'code'])
                    ->required()
                    ->translateLabel(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(9999)
                    ->translateLabel(),
                Forms\Components\TextInput::make('cost')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(9999)
                    ->translateLabel(),

            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__("Products"))
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('Required'))
                    ->numeric(decimalPlaces: 0, decimalSeparator: '.', thousandsSeparator: ',')
                    ->sortable()
                    ->searchable()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('cost')
                    ->translateLabel()
                    ->sortable()
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ','),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->getStateUsing(function ($record): float {
                        return round($record->cost * $record->quantity, 2);
                    })
                    ->alignEnd()
                    ->numeric(decimalPlaces: 2, decimalSeparator: '.', thousandsSeparator: ','),
                Tables\Columns\TextColumn::make('quantity_received')
                    ->numeric(decimalPlaces: 0, decimalSeparator: '.', thousandsSeparator: ',')
                    ->label(__('Received'))
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('pending')
                    ->getStateUsing(function ($record) {
                        return $record->quantity - $record->quantity_received;
                    })
                    ->translateLabel()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->since()
                    ->sortable()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->headerActions([

                Tables\Actions\CreateAction::make()
                    ->label(__('Add') . ' ' . __('Product'))
                    ->modalHeading(__('Add') . ' ' . __('Product') . ' ' .  __('to a')   . ' ' . __('Purchase Order'))
                    ->visible(function (DetailsRelationManager $livewire): bool {
                        $purchase = $livewire->getOwnerRecord();
                        return $purchase->status === StatusPurchaseEnum::abierto;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()
                    ->color('warning')
                    ->modalHeading(__('Edit') . ' ' . __('Product') . ' ' .  __('to a')   . ' ' . __('Purchase Order'))
                    ->visible(function (DetailsRelationManager $livewire): bool {
                        $purchase = $livewire->getOwnerRecord();
                        return $purchase->status === StatusPurchaseEnum::abierto;
                    }),
                Tables\Actions\DeleteAction::make()->button()
                    ->visible(function (DetailsRelationManager $livewire): bool {
                        $purchase = $livewire->getOwnerRecord();
                        return $purchase->status === StatusPurchaseEnum::abierto;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function visible($record): bool
    {
        $purchase = $record->getOwnerRecord();
        return $purchase->status === StatusPurchaseEnum::abierto;
    }
}
