<?php

namespace App\Filament\Widgets;

use App\Models\inventory;
use App\Models\sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class Margin extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {

        $start = $this->filters['startDate'] ?? Carbon::now()->subMonth()->startOfDay();
        $end = $this->filters['endDate'] ?? Carbon::now()->endOfDay();

        $inventoryData = $this->getStatsData(inventory::class, $start, $end, 'item');
        $salesData = $this->getStatsData(sale::class, $start, $end, 'dish');

        $margin = $salesData['current'] - $inventoryData['current'];

        // Get margin data for the last 6 months and current month
        $marginData = $this->getMarginData($inventoryData, $salesData);

        return [
            Stat::make('Total Inventories', '₹.' . $inventoryData['current'] . '/-')
                ->description($inventoryData['description'])
                ->descriptionIcon($inventoryData['icon'])
                ->chart($inventoryData['chart'])
                ->color($inventoryData['color']),
            Stat::make('Total Sales', '₹.' . $salesData['current'] . '/-')
                ->description($salesData['description'])
                ->descriptionIcon($salesData['icon'])
                ->chart($salesData['chart'])
                ->color($salesData['color']),
            Stat::make('Total Margin', '₹.' . $margin . '/-')
                ->description($marginData['description'])
                ->descriptionIcon($marginData['icon'])
                ->chart($marginData['chart'])
                ->color($marginData['color']),
        ];
    }

    private function getMarginData($inventoryData, $salesData)
    {
        // Calculate the margin for the current month
        $currentMargin = $salesData['current'] - $inventoryData['current'];

        // Calculate margin for the last 6 months
        $marginTotals = [];
        for ($i = 0; $i < 7; $i++) {
            $marginTotals[] = $salesData['chart'][$i] - $inventoryData['chart'][$i];
        }

        $previousSixMonthsTotal = array_sum(array_slice($marginTotals, 0, 6));

        return [
            'current' => $currentMargin,
            'description' => $this->getComparisonDescription($currentMargin, $previousSixMonthsTotal),
            'icon' => $this->getComparisonIcon($currentMargin, $previousSixMonthsTotal),
            'color' => $this->getComparisonColor($currentMargin, $previousSixMonthsTotal),
            'chart' => $this->generateChart($marginTotals),
        ];
    }

    private function getStatsData($modelClass, $startDate, $endDate, $relation)
    {
        // Calculate current month's total
        $currentTotal = $this->calculateTotal($modelClass, $startDate, $endDate, $relation);

        // Retrieve totals for each of the last 6 months
        $monthlyTotals = [];
        for ($i = 6; $i > 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i - 1)->startOfMonth()->subSecond();
            $monthlyTotals[] = $this->calculateTotal($modelClass, $monthStart, $monthEnd, $relation);
        }

        // Include current month's total in the chart data
        $monthlyTotals[] = $currentTotal;

        // Calculate the total for the last 6 months
        $previousSixMonthsTotal = array_sum(array_slice($monthlyTotals, 0, 6));

        // Prepare data for display
        return [
            'current' => $currentTotal,
            'description' => $this->getComparisonDescription($currentTotal, $previousSixMonthsTotal),
            'icon' => $this->getComparisonIcon($currentTotal, $previousSixMonthsTotal),
            'color' => $this->getComparisonColor($currentTotal, $previousSixMonthsTotal),
            'chart' => $this->generateChart($monthlyTotals),
        ];
    }

    private function calculateTotal($modelClass, $startDate, $endDate, $relation)
    {
        $query = $modelClass::where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate);

        $total = 0;
        foreach ($query->get() as $record) {
            foreach ($record[$relation] as $relatedItem) {
                $total += $relatedItem['price'];
            }
        }
        return $total;
    }


    private function getComparisonDescription($currentValue, $previousValue)
    {
        if ($previousValue == 0) {
            return $currentValue > 0
                ? '100% increase '
                : 'No change';
        } else {
            $difference = $currentValue - $previousValue;
            $percentageChange = ($difference / $previousValue) * 100;

            return $percentageChange > 0
                ? number_format($percentageChange, 2) . '% increase'
                : number_format(abs($percentageChange), 2) . '% decrease';
        }
    }

    private function getComparisonIcon($currentValue, $previousValue)
    {
        return $currentValue >= $previousValue ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    }

    private function getComparisonColor($currentValue, $previousValue)
    {
        return $currentValue >= $previousValue ? 'success' : 'danger';
    }

    private function generateChart($monthlyTotals)
    {
        // Return an array with data points for the last 6 months plus the current month
        return $monthlyTotals;
    }
}
