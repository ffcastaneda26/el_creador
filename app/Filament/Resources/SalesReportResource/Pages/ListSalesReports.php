<?php

namespace App\Filament\Resources\SalesReportResource\Pages;

use App\Filament\Resources\SalesReportResource;
use App\Filament\Resources\SalesReportResource\Widgets\SalesGoalsWidget;
use App\Filament\Resources\SalesReportResource\Widgets\SalesOverviewWidget;
use App\Filament\Resources\SalesReportResource\Widgets\SalesTrendWidget;
use App\Support\SalesAnalytics;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListSalesReports extends ListRecords
{
    protected static string $resource = SalesReportResource::class;

    protected function getHeaderActions(): array
    {
        $selectedPeriod = SalesAnalytics::periodFromRequest();

        return [
            $this->makePeriodAction('weekly', 'Semanal', $selectedPeriod),
            $this->makePeriodAction('monthly', 'Mensual', $selectedPeriod),
            $this->makePeriodAction('quarterly', 'Trimestral', $selectedPeriod),
            $this->makePeriodAction('annual', 'Anual', $selectedPeriod),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        $period = SalesAnalytics::periodFromRequest();

        return [
            SalesOverviewWidget::make(['period' => $period]),
            SalesTrendWidget::make(['period' => $period]),
            SalesGoalsWidget::make(['period' => $period]),
        ];
    }

    private function makePeriodAction(string $period, string $label, string $selectedPeriod): Action
    {
        return Action::make('period_' . $period)
            ->label($label)
            ->color($selectedPeriod === $period ? 'primary' : 'gray')
            ->url(fn (): string => static::getResource()::getUrl('index', ['period' => $period]));
    }
}
