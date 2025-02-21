<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Purchase;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use App\Enums\Enums\StatusPurchaseEnum;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PurchaseResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PurchaseResource\RelationManagers;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $activeNavigationIcon = 'heroicon-s-shield-check';
    protected static ?int $navigationSort = 42;

    public static function getNavigationGroup(): string
    {
        return __('Purchases');
    }
    public static function getModelLabel(): string
    {
        return __('Purchase Order');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Purchase Orders');
    }

    public static function form(Form $form): Form
    {
        // 'notes',
        // 'status',
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Select::make('provider_id')
                        ->relationship(
                            name: 'provider',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn(Builder $query) => $query->where('active', 1),
                        )
                        ->required()
                        ->translateLabel()
                        ->reactive()
                        ->live(onBlur: true)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('folio')
                        ->required()
                        ->maxLength(15)
                        ->unique(ignoreRecord: true)
                        ->translateLabel()
                        ->disabled(fn($operation) => $operation == 'edit'),

                    Forms\Components\DatePicker::make('date')
                        ->required()
                        ->default(now())
                        ->translateLabel()
                        ->format('Y-m-d'),

                    Forms\Components\TextInput::make('amount')
                        ->maxLength(30)
                        ->disabled()
                        ->numeric()
                        ->translateLabel(),
                    Forms\Components\Select::make('status')
                        ->options(StatusPurchaseEnum::class)
                        ->translateLabel()
                        ->visible(fn($operation) => $operation != 'create')
                        ->disabled(),
                ])->columns(2),
                Forms\Components\Group::make()->schema([
                    Forms\Components\MarkdownEditor::make('notes')
                        ->translateLabel()
                        ->columnSpanFull()
                        ->maxHeight('96px')
                        ->extraAttributes(['stype' => 'overflow-y:scroll;']),

                ])->columns(1),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('provider.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('folio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('authorizer_user.name')
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Action::make('authorize')
                    ->translateLabel()
                    ->button()
                    ->icon('heroicon-s-hand-thumb-up')
                    ->requiresConfirmation()
                    ->modalHeading(__('Authorize Request'))
                    ->modalDescription('¿Estás seguro de autorizar la orden de Compra?, No se puede deshacer esta acción.')
                    ->modalSubmitActionLabel(__('Yes, authorize it'))
                    ->stickyModalHeader()
                    ->closeModalByClickingAway(false)
                    ->closeModalByEscaping(false)
                    ->modalIconColor('danger')
                    ->visible(fn(Purchase $record): bool => $record->status === StatusPurchaseEnum::abierto)
                    ->action(action: function (Purchase $record) {
                        $record->status = StatusPurchaseEnum::autorizado;
                        $record->user_authorizer_id = Auth::user()->id;
                        $record->save();
                    }),
                // ->slideOver()

                Action::make('open')
                    ->translateLabel()
                    ->button()
                    ->icon('heroicon-s-lock-open')
                    ->requiresConfirmation()
                    ->modalHeading(__('Open Request'))
                    ->modalDescription('¿Estás seguro de abrir la Orden de Compra?, No se puede deshacer esta acción.')
                    ->modalSubmitActionLabel(__('Yes, open it'))
                    ->stickyModalHeader()
                    ->closeModalByClickingAway(false)
                    ->closeModalByEscaping(false)
                    ->modalIconColor('success')
                    ->visible(fn(Purchase $record): bool => $record->status === StatusPurchaseEnum::autorizado  && !$record->has_pendings_to_receive())
                    ->action(action: function (Purchase $record) {
                        $record->status = StatusPurchaseEnum::abierto;
                        $record->user_authorizer_id = null;

                        $record->save();
                    }),
                // ->slideOver()
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
