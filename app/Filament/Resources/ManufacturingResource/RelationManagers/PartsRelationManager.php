<?php

namespace App\Filament\Resources\ManufacturingResource\RelationManagers;

use Filament\Forms;
use App\Models\Part;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\DetailPart;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PartsRelationManager extends RelationManager
{
    protected static string $relationship = 'parts';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Parts of the Botarga');
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('part_id')
                    ->relationship(
                        name: 'part',
                        titleAttribute: 'name',
                    )
                    ->required()
                    ->reactive()
                    ->preload()
                    ->label(__('Parent Part'))
                    ->afterStateUpdated(fn (callable $set) => $set('child_part_id', null)),

                Forms\Components\Select::make('child_part_id')
                    ->required()
                    ->label(__('Child Part'))
                    ->options(function(callable $get){
                        $parent_part = Part::find($get('part_id'));
                        if(!$parent_part){
                            return;
                        }
                        $child_parts_ids = $parent_part->parts->select('child_part_id')->toArray();
                        return Part::whereIn('id',$child_parts_ids)->orderBy('id')->pluck('name','id');
                     }),

                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('color')
                        ->nullable()
                        ->maxLength(30)
                        ->label(__('Color')),
                    Forms\Components\TextInput::make('material')
                        ->nullable()
                        ->maxLength(30)
                        ->label(__('Material')),
                    Forms\Components\TextInput::make('value')
                        ->nullable()
                        ->maxLength(30)
                        ->label(__('Value')),
                    Forms\Components\Toggle::make('include')
                        ->label('Include')
                ])->columns(4),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('part.name')
            ->columns([
                Tables\Columns\TextColumn::make('part.name')->translateLabel(),
                Tables\Columns\TextColumn::make('child_part.name')->translateLabel(),
                Tables\Columns\TextColumn::make('material'),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\TextColumn::make('value')->translateLabel(),
                Tables\Columns\IconColumn::make('include')->translateLabel()->boolean()
            ])
            ->filters([
                SelectFilter::make('part_id')
                ->label(__('Parent Part'))
                ->multiple()
                ->relationship('part', 'name')
                ->preload(),
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
