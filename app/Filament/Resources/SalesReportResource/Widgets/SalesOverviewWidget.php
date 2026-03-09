<?php

namespace App\Filament\Resources\SalesReportResource\Widgets;

use App\Support\SalesAnalytics;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesOverviewWidget extends StatsOverviewWidget
{
    public string $period = 'monthly';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $period = SalesAnalytics::normalizePeriod($this->period);
        [$start, $end] = SalesAnalytics::range($period);

        $summary = SalesAnalytics::summary($period);
        $averageTicket = $summary['total_orders'] > 0
            ? round($summary['total_sales'] / $summary['total_orders'], 2)
            : 0;

        $rangeLabel = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');

        return [
            Stat::make('Ventas acumuladas', '$' . number_format($summary['total_sales'], 2))
                ->description('Periodo ' . SalesAnalytics::periodLabel($period) . ' (' . $rangeLabel . ')')
                ->color('success'),
            Stat::make('Ordenes de compra', number_format($summary['total_orders']))
                ->description('Ordenes registradas en el periodo')
                ->color('primary'),
            Stat::make('Ordenes facturadas', number_format($summary['invoiced_orders']))
                ->description('Tasa de facturacion: ' . number_format($summary['invoiced_rate'], 2) . '%')
                ->color('warning'),
            Stat::make('Ticket promedio', '$' . number_format($averageTicket, 2))
                ->description('Promedio por orden de compra')
                ->color('gray'),
        ];
    }
}
