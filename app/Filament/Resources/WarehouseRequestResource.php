<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\WarehouseRequest;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\Enums\StatusWarehouseRequestEnum;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Doctrine\DBAL\Driver\Mysqli\Initializer\Options;
use App\Filament\Resources\WarehouseRequestResource\Pages;
use App\Filament\Resources\WarehouseRequestResource\RelationManagers;

class WarehouseRequestResource extends Resource
{
    protected static ?string $model = WarehouseRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole('Administrador');
    }

    protected static ?int $navigationSort = 61;
    public static function getNavigationLabel(): string
    {
        return __('Warehouse Requests');
    }

    public static function getModelLabel(): string
    {
        return __('Warehouse Request');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Warehouse Requests');
    }
    public static function getNavigationGroup(): string
    {
        return __('Inventory');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\DatePicker::make('date')
                        ->required()
                        ->default(now())
                        ->translateLabel()
                        ->format('Y-m-d'),
                    Forms\Components\TextInput::make('folio')
                        ->required()
                        ->maxLength(15)
                        ->unique(ignoreRecord: true)
                        ->translateLabel()
                        ->disabled(fn($operation) => $operation == 'edit'),
                    Forms\Components\TextInput::make('reference')
                        ->maxLength(30)
                        ->translateLabel(),
                    Forms\Components\Select::make('status')
                        ->options(StatusWarehouseRequestEnum::class)
                        ->translateLabel()
                        ->required(fn($operation) => $operation == 'create')
                        ->disabled(),
                ])->columns(2),
                Forms\Components\Group::make()->schema([
                    Forms\Components\RichEditor::make('notes')
                        ->translateLabel()
                        ->columnSpanFull(),
                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('warehouse.name')
                    ->numeric()
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('folio')
                    ->searchable()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->sortable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('user_auhtorizer_id')
                    ->numeric()
                    ->sortable()
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(StatusWarehouseRequestEnum::class)
                    ->translateLabel()

            ])
            ->actions([
                Tables\Actions\ViewAction::make()->button()->color('info'),
                Tables\Actions\EditAction::make()->button()->color('indigo'),
                Action::make('authorize')
                    ->translateLabel()
                    ->button()
                    ->icon('heroicon-s-hand-thumb-up')
                    ->requiresConfirmation()
                    ->modalHeading(__('Authorize Request'))
                    ->modalDescription('¿Estás seguro de autorizar la solicitud?, No se puede deshacer esta acción.')
                    ->modalSubmitActionLabel(__('Yes, authorize it'))
                    ->stickyModalHeader()
                    ->closeModalByClickingAway(false)
                    ->closeModalByEscaping(false)
                    ->modalIconColor('danger')
                    ->visible(fn(WarehouseRequest $record): bool => $record->status === StatusWarehouseRequestEnum::abierto)
                    ->action(action: function (WarehouseRequest $record) {
                        $record->status = StatusWarehouseRequestEnum::autorizado;
                        $record->save();
                    }),
                // ->slideOver()

                Action::make('open')
                    ->translateLabel()
                    ->button()
                    ->icon('heroicon-s-lock-open')
                    ->requiresConfirmation()
                    ->modalHeading(__('Open Request'))
                    ->modalDescription('¿Estás seguro de abrir la solicitud?, No se puede deshacer esta acción.')
                    ->modalSubmitActionLabel(__('Yes, open it'))
                    ->stickyModalHeader()
                    ->closeModalByClickingAway(false)
                    ->closeModalByEscaping(false)
                    ->modalIconColor('success')
                    ->visible(fn(WarehouseRequest $record): bool => $record->status === StatusWarehouseRequestEnum::autorizado)
                    ->action(action: function (WarehouseRequest $record) {
                        $record->status = StatusWarehouseRequestEnum::abierto;
                        $record->save();
                    }),
                // ->slideOver()
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
            'index' => Pages\ListWarehouseRequests::route('/'),
            'create' => Pages\CreateWarehouseRequest::route('/create'),
            'view' => Pages\ViewWarehouseRequest::route('/{record}'),
            'edit' => Pages\EditWarehouseRequest::route('/{record}/edit'),
        ];
    }
}
