<?php

namespace App\Filament\Widgets;

use App\Models\dish;
use App\Models\inventory;
use App\Models\Item;
use App\Models\sale;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatsOverview extends BaseWidget
{

    protected function getStats(): array
    {

        // Define the current month period
        $currentMonthStart = Carbon::now()->startOfMonth(); // Start of current month
        $sixMonthsAgoStart = Carbon::now()->subMonths(6)->startOfMonth(); // Start of 6 months ago


        Log::warning($currentMonthStart . ',' . $sixMonthsAgoStart);

        // Retrieve data counts for the current month and the last 6 months
        $totalInventories = $this->getStatsData(inventory::class, $currentMonthStart, $sixMonthsAgoStart);
        $totalItems = $this->getStatsData(Item::class, $currentMonthStart, $sixMonthsAgoStart);
        $totalDishes = $this->getStatsData(dish::class, $currentMonthStart, $sixMonthsAgoStart);
        $totalSales = $this->getStatsData(sale::class, $currentMonthStart, $sixMonthsAgoStart);

        // Return the stats with appropriate labels, colors, and icons

        $stats =  [
            Stat::make('Total Inventories', $totalInventories['current'])
                ->description($totalInventories['description'])
                ->descriptionIcon($totalInventories['icon'])
                ->chart($totalInventories['chart'])
                ->color($totalInventories['color']),
            Stat::make('Total Items', $totalItems['current'])
                ->description($totalItems['description'])
                ->descriptionIcon($totalItems['icon'])
                ->chart($totalItems['chart'])
                ->color($totalItems['color']),
            Stat::make('Total Dishes', $totalDishes['current'])
                ->description($totalDishes['description'])
                ->descriptionIcon($totalDishes['icon'])
                ->chart($totalDishes['chart'])
                ->color($totalDishes['color']),
            Stat::make('Total Sales', $totalSales['current'])
                ->description($totalSales['description'])
                ->descriptionIcon($totalSales['icon'])
                ->chart($totalSales['chart'])
                ->color($totalSales['color']),
        ];

        return $stats;
    }

    private function getStatsData($modelClass, $currentMonthStart, $sixMonthsAgoStart)
    {
        // Count current month's data and data from the last 6 months
        $currentCount = $modelClass::where('updated_at', '>=', $currentMonthStart)->count();

        // Retrieve data counts for each of the last 6 months
        $monthlyCounts = [];
        for ($i = 6; $i > 0; $i--) {
            $monthStart = Carbon::now()->subMonths($i)->startOfMonth();
            $monthEnd = Carbon::now()->subMonths($i - 1)->startOfMonth()->subSecond();
            $monthlyCounts[] = $modelClass::whereBetween('updated_at', [$monthStart, $monthEnd])->count();
        }

        // Include current month's data in the chart
        $monthlyCounts[] = $currentCount;

        // Calculate the total for the last 6 months
        $previousSixMonthsCount = array_sum(array_slice($monthlyCounts, 0, 6));

        // Debugging: Return both counts for further checking
        return [
            'current' => $currentCount,
            'previous' => $previousSixMonthsCount,
            'description' => $this->getComparisonDescription($currentCount, $previousSixMonthsCount),
            'icon' => $this->getComparisonIcon($currentCount, $previousSixMonthsCount),
            'color' => $this->getComparisonColor($currentCount, $previousSixMonthsCount),
            'chart' => $this->generateChart($monthlyCounts),
        ];
    }

    private function getComparisonDescription($currentValue, $previousValue)
    {
        if ($previousValue == 0) {
            return $currentValue > 0
                ? '100% increase from the last 6 months'
                : 'No change from the last 6 months';
        } else {
            $difference = $currentValue - $previousValue;
            $percentageChange = ($difference / $previousValue) * 100;

            return $percentageChange > 0
                ? number_format($percentageChange, 2) . '% increase from the last 6 months'
                : number_format(abs($percentageChange), 2) . '% decrease from the last 6 months';
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

    private function generateChart($monthlyCounts)
    {
        // Create a simple chart comparing the data of each of the last 6 months with the current month's data
        return $monthlyCounts;
    }
}
