<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 70;


    public static function getNavigationGroup(): string
    {
        return __('Specials');
    }
    public static function getModelLabel(): string
    {
        return __('Event');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Events');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('title')
                        ->translateLabel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->translateLabel()
                        ->columnSpanFull(),
                ]),
                Forms\Components\Group::make([
                    Forms\Components\ColorPicker::make('color')
                        ->translateLabel()
                        ->default('#0df063')
                        ->required(),
                    Forms\Components\DateTimePicker::make('starts_at')
                        ->translateLabel()
                        ->default(now())
                        ->required(),
                    Forms\Components\DateTimePicker::make('ends_at')
                        ->translateLabel()
                        ->default(now())
                        ->required(),
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->translateLabel()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('color')
                //     ->translateLabel()
                //     ->searchable(),
                Tables\Columns\TextColumn::make('color')
                    ->translateLabel()
                    ->formatStateUsing(function ($state) {
                        return '<div style="background-color: ' . $state . '; width: 30px; height: 30px; border-radius: 50%;"></div>';
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
