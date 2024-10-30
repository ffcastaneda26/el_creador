<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MunicipalityResource\Pages;
use App\Filament\Resources\MunicipalityResource\RelationManagers;
use App\Models\Municipality;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MunicipalityResource extends Resource
{
    protected static ?string $model = Municipality::class;


    protected static ?string $navigationIcon = 'heroicon-o-map';


    protected static ?int $navigationSort = 6;

    // protected static ?string $cluster = Geographics::class;
    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }
    public static function getModelLabel(): string
    {
        return __('Municipality');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Municipalities');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('state_id')
                    ->relationship('state', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('state.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Municipality'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                    SelectFilter::make('state')
                    ->translateLabel()
                    ->relationship('state', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->visible(Auth::user()->isSuperAdmin())
                ->disabled(!Auth::user()->isSuperAdmin())

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
            'index' => Pages\ListMunicipalities::route('/'),
            'create' => Pages\CreateMunicipality::route('/create'),
            'edit' => Pages\EditMunicipality::route('/{record}/edit'),
        ];
    }
}
