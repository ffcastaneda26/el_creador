<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Manufacturing;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Filament\Resources\ManufacturingResource\Pages;
use App\Filament\Resources\ManufacturingResource\RelationManagers;
use App\Filament\Resources\ManufacturingResource\RelationManagers\PartsRelationManager;
use App\Filament\Resources\ManufacturingResource\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\ManufacturingResource\RelationManagers\LogotiposRelationManager;

class ManufacturingResource extends Resource
{
    protected static ?string $model = Manufacturing::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    protected static ?int $navigationSort = 30;

    public static function getNavigationGroup(): string
    {
        return __('Sales');
    }
    public static function getModelLabel(): string
    {
        return __('Manufacturing Order');
    }


    public static function getPluralLabel(): ?string
    {
        return __('Manufacturing Orders');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make(__('Generals'))
                            ->schema([
                                Group::make()->schema([
                                    TextInput::make('folio')
                                        ->default(function () {
                                            return Manufacturing::query()->max('id') + 1;
                                        })
                                        ->readOnly()
                                        ->helperText(__('Next Folio')),
                                    Select::make('order_id')
                                        ->relationship(
                                            name: 'order',
                                            titleAttribute: 'id',
                                        )
                                        // ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->id} {$record->customer->name}")
                                        ->getOptionLabelFromRecordUsing( function (Model $record) {
                                            return 'No.' . ' ' . $record->id . '=' . $record->client->name;
                                        })
                                        ->required()
                                        ->preload()
                                        ->label(__('Purchase Order'))
                                        ->helperText(__('Select Purchase Order'))
                                        ->columnSpan(2),
                                    TextInput::make('botarga')
                                        ->translateLabel()
                                        ->required()
                                        ->helperText(__("Motley's Name"))
                                        ->columnSpan(2),
                                ])->columns(5),
                                Group::make()->schema([
                                    Select::make('asesor_id')
                                        ->label(__('Advisor in Charge'))
                                        ->options(User::role('asesor')->pluck('name', 'id'))
                                        ->searchable()
                                        ->required()
                                        ->helperText('Selecciona al asesor responsable'),
                                    DatePicker::make('fecha_inicio')
                                        ->label(__('Start Date'))
                                        ->required(),
                                    DatePicker::make('fecha_fin')
                                        ->label(__('End Date'))
                                ])->columns(3),
                            ])->icon('heroicon-m-briefcase'),
                        Tabs\Tab::make(__('Observations'))
                            ->schema([
                                Tabs::make()->tabs([
                                    Tabs\Tab::make(__('Head & Body'))->schema([
                                        MarkdownEditor::make('observaciones_cabeza')
                                            ->label(__('Head')),
                                        MarkdownEditor::make('observaciones_cuerpo')
                                            ->label(__('Body')),
                                    ])->columns(2),

                                    Tabs\Tab::make(__('Structure & Internal Body'))->schema([
                                        MarkdownEditor::make('observaciones_estructura')
                                            ->label(__('Structure')),
                                        MarkdownEditor::make('observaciones_body_interno')
                                            ->label(__('Internal Body')),
                                    ])->columns(2),

                                    Tabs\Tab::make(__('Outfits'))->schema([
                                        MarkdownEditor::make('observaciones_outfit1')
                                            ->label('Outfit 1'),
                                        MarkdownEditor::make('observaciones_outfit2')
                                            ->label('Outfit 2'),
                                    ])->columns(2),

                                    Tabs\Tab::make(__('Shoes - Accesories - Logos'))->schema([
                                        MarkdownEditor::make('observaciones_zapatos')
                                            ->label(__('Shoes')),
                                        MarkdownEditor::make('observaciones_accesorios')
                                            ->label(__('Accesories')),
                                        MarkdownEditor::make('observaciones_logotipos')
                                            ->label(__('Logos'))
                                    ])->columns(3),
                                ])->columnSpanFull(),

                            ])->icon('heroicon-m-eye')
                            ->columns(2),
                    ])->contained(false),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('folio')
                    ->sortable()
                    ->searchable()
                    ->label(__('Folio')),

                Tables\Columns\TextColumn::make('order')
                    ->label(__('Purchase Order'))
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(function (Manufacturing $record) {
                        return 'No.' . ' ' . $record->order->id . ' ' . $record->order->client->name;
                    }),
                Tables\Columns\TextColumn::make('asesor.name')
                    ->label('Asesor'),
                Tables\Columns\TextColumn::make('botarga')
                    ->label(__('Motley')),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->searchable()
                    ->sortable()
                    ->label(__('Start Date'))
                    ->date('d M y'),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->searchable()
                    ->sortable()
                    ->label(__('End Date'))
                    ->date('d M y'),
                Tables\Columns\TextColumn::make('observaciones_cabeza')
                    ->label(__('Head Notes'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('observaciones_cuerpo')
                    ->label(__('Body Notes'))
                   ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('observaciones_estructura')
                    ->label(__('Structure Notes'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('observaciones_body_interno')
                    ->label(__('Internal Body Notes'))
                   ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('observaciones_outfit1')
                    ->label(__('Outfit 1 Notes'))
                   ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('observaciones_outfit2')
                    ->label(__('Outfit 2 Notes'))
                   ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('observaciones_zapatos')
                    ->label(__('Shoes Notes'))
                   ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('observaciones_accesorios')
                    ->label(__('Accesories Notes'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('observaciones_logotipos')
                    ->label(__('Logos Notes'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel()
                    ->limit(50),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('User Name'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),


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
            LogotiposRelationManager::class,
            PartsRelationManager::class,
            ImagesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListManufacturings::route('/'),
            'create' => Pages\CreateManufacturing::route('/create'),
            'edit' => Pages\EditManufacturing::route('/{record}/edit'),
        ];
    }
}
