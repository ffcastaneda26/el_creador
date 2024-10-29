<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;


    public static function getNavigationIcon(): string|Htmlable|null
    {
        return 'heroicon-o-users';
    }
    public static function getNavigationGroup(): string
    {
        return   __('Security');
    }

    // protected static ?int $navigationSort = 6;
    public static function getNavigationSort(): ?int
    {
        return 1;
    }
    public static function getNavigationLabel(): string
    {
        return __('Users');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();

    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 1 ? 'danger' : 'success';
    }

    public static function getEloquentQuery(): Builder
    {
        if(Auth::user()->hasrole('Super Admin')){
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->whereHas('roles',function($query){
                $query->where('name','not like','%super%');
        });

    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->minLength(10)
                    ->maxLength(100)
                    ->translateLabel(),
                TextInput::make('email')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->translateLabel()
                    ->maxLength(100)
                    ->minLength(10),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->translateLabel()
                    ->maxLength(30)
                    ->minLength(8),
                Select::make('role_id')
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn(Builder $query) => $query->whereNotIn('name', ['Super Admin'])
                    )
                    ->multiple()
                    ->translateLabel()
                    ->preload()
                    ->required()

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
            ])
            ->filters([
                SelectFilter::make(__('Role'))
                ->relationship('roles', 'name')
                ->translateLabel()
                ->searchable()
                ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
