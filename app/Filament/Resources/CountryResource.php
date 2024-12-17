<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CountryResource\Pages;
use App\Filament\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('Countries');
    }

    // protected static ?string $cluster = Geographics::class;
    public static function getNavigationGroup(): string
    {
        return __('Geographic');
    }

    public static function getModelLabel(): string
    {
        return __('Country');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Countries');
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('include','desc')
            ->orderby('country');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('country')->disabled(),
                TextInput::make('code')->disabled(),
                Toggle::make('include')->label(__('Include?'))
                    ->inline(false)
                    ->onIcon('heroicon-m-check-circle')
                    ->offIcon('heroicon-m-x-circle')
                    ->onColor('success')
                    ->offColor('danger')
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('country')->label(__('Country'))->searchable()->sortable(),
                TextColumn::make('code')->label(__('Code'))->searchable()->sortable(),
                ToggleColumn::make('include')->label(__('Include?'))
            ])
            ->filters([
                TernaryFilter::make('include'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }
}
