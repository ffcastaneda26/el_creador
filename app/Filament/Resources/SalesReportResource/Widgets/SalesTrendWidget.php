<?php

namespace App\Filament\Resources\SalesReportResource\Widgets;

use App\Support\SalesAnalytics;
use Filament\Widgets\ChartWidget;

class SalesTrendWidget extends ChartWidget
{
    public string $period = 'monthly';

    protected static ?string $heading = 'Tendencia de ventas';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $trend = SalesAnalytics::trend(SalesAnalytics::normalizePeriod($this->period));

        if (count($trend['labels']) === 0) {
            $trend['labels'] = ['Sin datos'];
            $trend['values'] = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ventas (MXN)',
                    'data' => $trend['values'],
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.18)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $trend['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        return 'Periodo: ' . SalesAnalytics::periodLabel(SalesAnalytics::normalizePeriod($this->period));
    }
}
