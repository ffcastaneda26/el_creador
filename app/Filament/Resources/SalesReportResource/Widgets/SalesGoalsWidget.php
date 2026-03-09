<?php

namespace App\Filament\Resources\SalesReportResource\Widgets;

use App\Support\SalesAnalytics;
use Filament\Widgets\ChartWidget;

class SalesGoalsWidget extends ChartWidget
{
    public string $period = 'monthly';

    protected static ?string $heading = 'Metas por empleado';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '320px';

    protected ?array $progress = null;

    protected function getData(): array
    {
        $progress = $this->progressData();

        if (count($progress['labels']) === 0) {
            $progress['labels'] = ['Sin metas'];
            $progress['goals'] = [0];
            $progress['achieved'] = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Meta (MXN)',
                    'data' => $progress['goals'],
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)',
                    'borderColor' => '#f59e0b',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Vendido (MXN)',
                    'data' => $progress['achieved'],
                    'backgroundColor' => 'rgba(34, 197, 94, 0.45)',
                    'borderColor' => '#22c55e',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $progress['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getDescription(): ?string
    {
        $progress = $this->progressData();

        return sprintf(
            'Meta total: $%s | Vendido: $%s',
            number_format((float) $progress['goal_total'], 2),
            number_format((float) $progress['achieved_total'], 2),
        );
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    private function progressData(): array
    {
        if ($this->progress !== null) {
            return $this->progress;
        }

        $this->progress = SalesAnalytics::goalsProgress(SalesAnalytics::normalizePeriod($this->period));

        return $this->progress;
    }
}
