<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;
use Spatie\Permission\Models\Permission;

class UserResource extends Resource
{
    protected static ?string $model = User::class;


    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function getNavigationGroup(): string
    {
        return __('Security');
    }

    // protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }

    public static function getNavigationBadge(): ?string
    {
        if (Auth::user()->hasrole('Super Admin')) {
            return static::getModel()::count();
        }

        return parent::getEloquentQuery()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'not like', '%super%');
            })->count();

    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 1 ? 'danger' : 'success';
    }

    public static function getEloquentQuery(): Builder
    {
        if (Auth::user()->hasrole('Super Admin')) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'not like', '%super%');
            });

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        TextInput::make('name')
                            ->required()
                            ->minLength(length: 5)
                            ->maxLength(100)
                            ->translateLabel(),
                            // ->columnSpanFull(),
                        TextInput::make('email')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->translateLabel()
                            ->maxLength(100)
                            ->minLength(5),
                            // ->columnSpanFull(),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->translateLabel()
                            ->maxLength(30)
                            ->minLength(8)
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create'),
                        Toggle::make('active'),
                    ])->columns(2),


                ]),
                Group::make()->schema([
                    // CheckboxList::make('roles')
                    //     ->relationship(name: 'roles', titleAttribute: 'name'),

                    Select::make('roles')
                        ->relationship(
                            name: 'roles',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn(Builder $query) => $query->whereNotIn('name', ['Super Admin'])
                        )
                        ->multiple()
                        ->translateLabel()
                        ->preload()
                        ->required(fn($state, $record) => $record ? false : true)
                        ->visible(fn($state, $record) => $record ? false : true),

                    // CheckboxList::make('permissions')
                    //     ->relationship(name: 'rolpermissionses', titleAttribute: 'name')
                            // ->visible(fn() => Permission::count()),

                     Select::make('permissions')
                        ->relationship('permissions', 'name')
                        ->label('Permisos')->translateLabel()
                        ->multiple()
                        ->preload()
                        ->visible(fn() => Permission::count()),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')->label(__('email'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('roles.name')->label('Roles')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('active')->translateLabel()->boolean(),

            ])
            ->filters([
                SelectFilter::make(__('Role'))
                    ->relationship('roles', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->preload(),
                SelectFilter::make(__('Permission'))
                    ->relationship('permissions', 'name')
                    ->translateLabel()
                    ->searchable()
                    ->preload()
                    ->visible(fn() => Permission::count()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
