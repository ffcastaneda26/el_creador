<?php

namespace App\Filament\Resources\PartResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartsRelationManager extends RelationManager
{
    protected static string $relationship = 'parts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('child_part_id')
                ->relationship('child_part', 'name')
                ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__("Motley's Parts"))
            ->columns([
                Tables\Columns\TextColumn::make('child_part.name')
                ->label(__('Part')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label(__('Add Part')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
