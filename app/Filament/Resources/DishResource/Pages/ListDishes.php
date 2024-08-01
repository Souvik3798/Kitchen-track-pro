<?php

namespace App\Filament\Resources\DishResource\Pages;

use App\Filament\Resources\DishResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ListDishes extends ListRecords
{
    protected static string $resource = DishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'today' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('updated_at', Carbon::today())),
            'this_week' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])),
            'this_month' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereMonth('updated_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)),
        ];
    }
}
