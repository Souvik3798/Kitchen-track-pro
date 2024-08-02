<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\dish;
use App\Models\Item;
use App\Models\sale;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditSale extends EditRecord
{
    protected static string $resource = SaleResource::class;

    protected $previousDishData;
    protected $deletedDishes = [];


    protected function beforeSave()
    {
        $this->previousDishData = $this->record->dish;
        logger($this->previousDishData);

        // Track deleted items
        $currentDishes = collect($this->data['dish'])->pluck('dish_id')->toArray();
        $previousDishes = collect($this->previousDishData)->pluck('dish_id')->toArray();
        $this->deletedDishes = array_diff($previousDishes, $currentDishes);
    }

    protected function afterSave()
    {
        $sales = $this->record->dish;
        logger($sales);

        // Update quantities for deleted items
        foreach ($this->deletedDishes as $deletedDishId) {
            $deletedDish = collect($this->previousDishData)->firstWhere('dish_id', $deletedDishId);
            $this->updateQuantitiesForDeletedDish($deletedDish);
        }

        // Update quantities for updated items
        foreach ($sales as $index => $sale) {
            $dishes = Dish::findorfail($sale['dish_id']);
            logger($dishes);

            $previousQuantity = $this->previousDishData[$index]['quantity'] ?? 0;

            foreach ($dishes['items'] as $items) {
                $item = Item::findorfail($items['item_id']);
                $item->update([
                    'quantity' => $item->quantity - ($sale['quantity'] - $previousQuantity) * $items['quantity'],
                ]);
                logger($item);
            }
        }
    }

    protected function updateQuantitiesForDeletedDish($deletedDish)
    {
        $dishes = Dish::findorfail($deletedDish['dish_id']);
        foreach ($dishes['items'] as $items) {
            $item = Item::findorfail($items['item_id']);
            $item->update([
                'quantity' => $item->quantity + ($deletedDish['quantity'] * $items['quantity']),
            ]);
            logger($item);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function saledelete($record)
    {
        $sales = $record->dish;
        logger($sales);
        foreach ($sales as $sale) {
            $dishes = dish::findorfail($sale['dish_id']);
            logger($dishes);

            foreach ($dishes['items'] as $items) {
                $item = Item::findorfail($items['item_id']);
                $item->update([
                    'quantity' => $item->quantity + ($sale['quantity'] * $items['quantity']),
                ]);
                logger($item);
            }
        }
        $sale = sale::findorfail($record->id);
        $sale->delete();
        $this->redirect(route('filament.admin.resources.sales.index'));
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
