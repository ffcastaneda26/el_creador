<?php

namespace App\Filament\Resources\WarehouseRequestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Group::make()->schema([
                    // TODO:: Validar para que no se duplique en la misma solicitud
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
                // ]),


            ]);
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity_delivered')
                    ->numeric(decimalPlaces: 0, decimalSeparator: '.', thousandsSeparator: ',')
                    ->label(__('Delivered')),
                Tables\Columns\TextColumn::make('pending')
                    ->translateLabel(),
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
                    ->label(__('Add Product')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button()->color('warning'),
                Tables\Actions\DeleteAction::make()->button(),
                // Tables\Actions\Action::make('suply')
                //     ->label(__('Suply'))
                //     ->button()
                //     ->color('info')
                //     ->icon('heroicon-s-shield-check')
                //     ->form([
                //         Forms\Components\Group::make()->schema([
                //         Forms\Components\TextInput::make('quantity')
                //             ->required()
                //             ->numeric()
                //             ->minValue(1)
                //             ->maxValue(9999)
                //             ->translateLabel(),
                //         ]),

                //     ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
