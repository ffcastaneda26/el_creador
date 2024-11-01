<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
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
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;
    protected static ?string $navigationIcon = 'heroicon-o-check';

    protected static ?int $navigationSort = 3;
    // Se mueve a una navegación dentro de la página principal
    // protected static ?string $cluster = Security::class;

    // Se abrirá hasta que se abra Roles
    //  protected static ?string $navigationParentItem = 'Roles';
    public static function getNavigationGroup(): string
    {
        return __('Security');
    }
    public static function getNavigationLabel(): string
    {
        return __('Permissions');
    }


    public static function getModelLabel(): string
    {
        return __('Permission');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Permissions');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    // public static function getNavigationGroup(): string {
    //     return __('Security');
    // }

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
                    Select::make('roles')
                        ->multiple()
                        ->relationship(titleAttribute:'name')
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->required()
                                ->unique()
                        ]),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre'),
                TextColumn::make('users_count')->counts('users')->label('Usuarios'),
                TextColumn::make('roles.name')->label('Roles'),
            ])
            ->filters([
                SelectFilter::make(__('Users'))
                    ->relationship('users', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->preload(),
                SelectFilter::make(__('Roles'))
                        ->relationship('roles', 'name')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
