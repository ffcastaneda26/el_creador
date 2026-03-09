<?php

namespace App\Support;

use App\Models\Goal;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SalesAnalytics
{
    public static function normalizePeriod(?string $period): string
    {
        return match ($period) {
            'weekly', 'monthly', 'quarterly', 'annual' => $period,
            default => 'monthly',
        };
    }

    public static function periodFromRequest(): string
    {
        return self::normalizePeriod((string) request()->query('period', 'monthly'));
    }

    public static function periodLabel(string $period): string
    {
        return match (self::normalizePeriod($period)) {
            'weekly' => 'Semanal',
            'monthly' => 'Mensual',
            'quarterly' => 'Trimestral',
            'annual' => 'Anual',
        };
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function range(string $period): array
    {
        $period = self::normalizePeriod($period);
        $now = now();

        return match ($period) {
            'weekly' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'quarterly' => [$now->copy()->startOfQuarter(), $now->copy()->endOfQuarter()],
            'annual' => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            default => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()],
        };
    }

    public static function baseOrdersQuery(string $period)
    {
        [$start, $end] = self::range($period);

        return Order::query()->whereBetween('date', [$start, $end]);
    }

    /**
     * @return array{total_sales: float, total_orders: int, invoiced_orders: int, invoiced_rate: float}
     */
    public static function summary(string $period): array
    {
        $query = self::baseOrdersQuery($period);

        $totalSales = (float) (clone $query)->sum('total');
        $totalOrders = (int) (clone $query)->count();
        $invoicedOrders = (int) (clone $query)->where('require_invoice', true)->count();

        return [
            'total_sales' => round($totalSales, 2),
            'total_orders' => $totalOrders,
            'invoiced_orders' => $invoicedOrders,
            'invoiced_rate' => $totalOrders > 0 ? round(($invoicedOrders / $totalOrders) * 100, 2) : 0,
        ];
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, float>}
     */
    public static function trend(string $period): array
    {
        $period = self::normalizePeriod($period);

        $groupExpression = match ($period) {
            'annual' => "DATE_FORMAT(date, '%Y-%m')",
            'quarterly' => "DATE_FORMAT(date, '%x-W%v')",
            default => "DATE_FORMAT(date, '%Y-%m-%d')",
        };

        $rows = self::baseOrdersQuery($period)
            ->selectRaw("{$groupExpression} as period_label")
            ->selectRaw('SUM(total) as total_sales')
            ->groupBy('period_label')
            ->orderBy('period_label')
            ->get();

        return [
            'labels' => $rows->pluck('period_label')->map(fn ($label) => (string) $label)->all(),
            'values' => $rows->pluck('total_sales')->map(fn ($value) => round((float) $value, 2))->all(),
        ];
    }

    /**
     * @return array{labels: array<int, string>, goals: array<int, float>, achieved: array<int, float>, goal_total: float, achieved_total: float}
     */
    public static function goalsProgress(string $period): array
    {
        [$start, $end] = self::range($period);

        $goals = Goal::query()
            ->with('user:id,name')
            ->whereDate('start_date', '<=', $end)
            ->whereDate('end_date', '>=', $start)
            ->get();

        $actualByUser = self::baseOrdersQuery($period)
            ->whereNotNull('user_id')
            ->selectRaw('user_id, SUM(total) as sold')
            ->groupBy('user_id')
            ->pluck('sold', 'user_id');

        $groupedGoals = $goals
            ->filter(fn (Goal $goal) => $goal->user_id !== null)
            ->groupBy('user_id')
            ->map(function (Collection $items) {
                return round((float) $items->sum(fn (Goal $goal) => (float) ($goal->goal_amount ?? 0)), 2);
            });

        $userIds = $groupedGoals->keys()->merge($actualByUser->keys())->unique()->values();

        $labels = [];
        $goalsData = [];
        $achievedData = [];

        foreach ($userIds as $userId) {
            $goalItems = $goals->where('user_id', (int) $userId);
            $userName = (string) ($goalItems->first()?->user?->name ?? ('Usuario #' . $userId));

            $labels[] = $userName;
            $goalsData[] = round((float) ($groupedGoals[$userId] ?? 0), 2);
            $achievedData[] = round((float) ($actualByUser[$userId] ?? 0), 2);
        }

        return [
            'labels' => $labels,
            'goals' => $goalsData,
            'achieved' => $achievedData,
            'goal_total' => round(array_sum($goalsData), 2),
            'achieved_total' => round(array_sum($achievedData), 2),
        ];
    }
}
