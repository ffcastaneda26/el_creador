<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 2;
    // protected static ?string $cluster = Security::class;

    public static function getNavigationGroup(): string
    {
        return __('Security');
    }
    public static function getNavigationLabel(): string
    {
        return __('Roles');
    }


    public static function getModelLabel(): string
    {
        return __('Role');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Roles');
    }

    // public static function getNavigationGroup(): string {
    //     return __('Security');
    // }

    public static function getNavigationBadge(): ?string
    {
        if(Auth::user()->hasrole('Super Admin')){
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()
            ->where('name','not like','%super%')
            ->count();
    }
    public static function getEloquentQuery(): Builder
    {
        if(Auth::user()->hasrole('Super Admin')){
            return parent::getEloquentQuery();
        }
        return parent::getEloquentQuery()->where('name','not like','%super%');

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->minLength(5)
                            ->translateLabel(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->translateLabel()->searchable()->sortable(),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Usuarios'),
                TextColumn::make('permissions.name')->label('Permisos')

            ])
            ->filters([
                SelectFilter::make(__('Users'))
                    ->relationship('users', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->preload(),
                SelectFilter::make(__('Permissions'))
                            ->relationship('permissions', 'name')
                            ->translateLabel()
                            ->searchable()
                            ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PermissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
