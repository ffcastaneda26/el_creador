<?php

namespace App\Filament\Resources;

use App\Enums\Enums\GoalPeriodEnum;
use App\Filament\Resources\GoalResource\Pages;
use App\Filament\Resources\GoalResource\RelationManagers;
use App\Models\Goal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GoalResource extends Resource
{
    protected static ?string $model = Goal::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 62;


    public static function getNavigationGroup(): string
    {
        return __('Goal Achievement');
    }

    public static function getModelLabel(): string
    {
        return __('Goal');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Goals');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Select::make('metric_id')
                        ->relationship(name: 'metric', titleAttribute: 'name')
                        ->translateLabel(),
                    Forms\Components\Select::make('user_id')
                        ->relationship(name: 'user', titleAttribute: 'name')
                        ->translateLabel(),
                    Forms\Components\TextInput::make('goal_units')
                        ->numeric()
                        ->translateLabel(),
                    Forms\Components\TextInput::make('goal_amount')
                        ->numeric()
                        ->translateLabel(),
                ])->columns(2),
                Forms\Components\Group::make()->schema([

                    Forms\Components\Select::make('period')
                        ->translateLabel()
                        ->options(GoalPeriodEnum::class)
                        ->required(),
                    Forms\Components\DatePicker::make('start_date')
                        ->translateLabel()
                        ->required(),
                    Forms\Components\DatePicker::make('end_date')
                        ->required()
                        ->translateLabel(),
                ])->columns(3),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('metric.name')
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('period'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('goal_units')
                    ->numeric()
                    ->translateLabel()
                    ->sortable()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('goal_amount')
                    ->numeric()
                    ->translateLabel()
                    ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format($state, 2, '.', ','))
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->translateLabel()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListGoals::route('/'),
            'create' => Pages\CreateGoal::route('/create'),
            'edit' => Pages\EditGoal::route('/{record}/edit'),
        ];
    }
}
