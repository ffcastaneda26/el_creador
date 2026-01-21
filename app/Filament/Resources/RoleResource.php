<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use BezhanSalleh\FilamentShield\Forms\ShieldSelectAllToggle;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    use HasShieldFormComponents;

    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 2;
    // protected static ?string $cluster = Security::class;
    protected static ?string $recordTitleAttribute = 'name';

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
            return static::getModel()::count();
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
                        Section::make()
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->minLength(5)
                                    ->translateLabel(),
                                TextInput::make('guard_name')
                                    ->maxLength(255)
                                    ->default(config('auth.defaults.guard'))
                                    ->translateLabel(),
                                ShieldSelectAllToggle::make('select_all')
                                    ->onIcon('heroicon-s-shield-check')
                                    ->offIcon('heroicon-s-shield-exclamation')
                                    ->label(__('Select all permissions'))
                                    ->dehydrated(fn (bool $state): bool => $state),
                            ])
                            ->columns(2),
                    ])->columns(1),
                static::getShieldFormComponents(),
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
                TextColumn::make('permissions.name')->label(__('Permissions'))

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
            //
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
