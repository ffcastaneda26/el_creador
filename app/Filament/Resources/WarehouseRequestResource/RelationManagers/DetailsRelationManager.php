<?php

namespace App\Filament\Resources\WarehouseRequestResource\RelationManagers;

use App\Enums\Enums\StatusWarehouseRequestEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->translateLabel()
                    ->validationMessages([
                        'unique' => 'El Producto ya está incluido necesita editarlo.',
                    ]),
                Forms\Components\TextInput::make('quantity')
                    ->required()
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
                Tables\Columns\TextColumn::make('quantity_delivered')
                    ->numeric(decimalPlaces: 0, decimalSeparator: '.', thousandsSeparator: ',')
                    ->label(__('Delivered'))
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('pending')
                    ->getStateUsing(function ($record) {
                        return $record->quantity - $record->quantity_delivered;
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
                    ->modalHeading(__('Add') . ' ' . __('Product') . ' ' .  __('to a Warehouse Request'))
                    ->visible(function (DetailsRelationManager $livewire): bool {
                        $wareHouseRequest = $livewire->getOwnerRecord();
                        return $wareHouseRequest->status === StatusWarehouseRequestEnum::abierto;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->button()->color('warning')
                    ->modalHeading(__('Edit') . ' ' . __('Product') . ' ' .  __('to a Warehouse Request'))
                    ->visible(function (DetailsRelationManager $livewire): bool {
                        $wareHouseRequest = $livewire->getOwnerRecord();
                        return $wareHouseRequest->status === StatusWarehouseRequestEnum::abierto;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->visible(function (DetailsRelationManager $livewire): bool {
                        $wareHouseRequest = $livewire->getOwnerRecord();
                        return $wareHouseRequest->status === StatusWarehouseRequestEnum::abierto;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function is_visible($record): bool
    {
        $ownerRecord = $record->getOwnerRecord();
        return $ownerRecord->status === StatusWarehouseRequestEnum::abierto;
    }
}
