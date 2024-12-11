<?php

namespace App\Filament\Resources\ManufacturingResource\RelationManagers;

use App\Models\LogosManufacturing;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class LogotiposRelationManager extends RelationManager
{
    protected static string $relationship = 'logotipos';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Comments About Motley Logos');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('anexo_id')
                    ->relationship(
                        name: 'anexo',
                        titleAttribute: 'anexo',
                        modifyQueryUsing: fn (Builder $query) => $query->whereDoesntHave('logotipos')->orderBy('id'),
                    )
                    ->required()
                    ->label('Anexo')
                    ->visible(fn($operation) => $operation == 'create')
                    ->disabled(fn($operation) => $operation != 'create'),
                Forms\Components\Select::make('anexo_id')
                    ->relationship(
                        name: 'anexo',
                        titleAttribute: 'anexo')
                    ->required()
                    ->label('Anexo')
                    ->visible(fn($operation) => $operation != 'create')
                    ->disabled(fn($operation) => $operation != 'create'),
                Forms\Components\TextInput::make('ubicacion')
                    ->nullable()
                    ->label(__('Ubication'))
                    ->maxLength(30),
                Forms\Components\TextInput::make('material')
                    ->nullable()
                    ->maxLength(30),
                Forms\Components\TextInput::make('tamano')
                    ->nullable()
                    ->label(__(key: 'Size'))
                    ->maxLength(30),
            ])->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('anexo')
            ->columns([
                Tables\Columns\TextColumn::make('anexo.anexo'),
                Tables\Columns\TextColumn::make('ubicacion')->label(__('Ubication')),
                Tables\Columns\TextColumn::make('material')->label(__('Material')),
                Tables\Columns\TextColumn::make('tamano')->label(__('Size')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label(__('Add Logo Comment')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
