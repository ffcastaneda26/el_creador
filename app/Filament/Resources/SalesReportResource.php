<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesReportResource\Pages;
use App\Models\Order;
use App\Support\SalesAnalytics;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SalesReportResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?int $navigationSort = 33;

    public static function getNavigationGroup(): string
    {
        return __('Sales');
    }

    public static function getModelLabel(): string
    {
        return __('Sales Report');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Sales Reports');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('Date'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.full_name')
                    ->label(__('Client'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('motley_name')
                    ->label('Botarga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Empleado')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('require_invoice')
                    ->label('Facturada')
                    ->boolean(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->label(__('Subtotal'))
                    ->money('MXN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tax')
                    ->label(__('Tax'))
                    ->money('MXN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('retencion_isr')
                    ->label('Retencion ISR')
                    ->money('MXN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('Total'))
                    ->money('MXN')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Empleado')
                    ->relationship('user', 'name')
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('require_invoice')
                    ->label('Facturada')
                    ->trueLabel('Solo facturadas')
                    ->falseLabel('Solo no facturadas')
                    ->native(false),
                Filter::make('date_range')
                    ->label('Rango de fechas')
                    ->form([
                        DatePicker::make('from')->label('Desde'),
                        DatePicker::make('until')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('date', 'desc')
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesReports::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        [$start, $end] = SalesAnalytics::range(SalesAnalytics::periodFromRequest());

        return parent::getEloquentQuery()
            ->with(['client:id,name,last_name,mother_surname,company_name,type', 'user:id,name'])
            ->whereBetween('date', [$start, $end]);
    }
}
