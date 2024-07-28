<?php

namespace App\Filament\Resources\SaleResource\Pages;

use App\Filament\Resources\SaleResource;
use App\Models\dish;
use App\Models\Item;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    protected static string $resource = SaleResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function afterCreate()
    {
        $sales = $this->record->dish;
        logger($sales);
        foreach ($sales as $sale) {
            $dishes = dish::findorfail($sale['dish_id']);
            logger($dishes);

            foreach ($dishes['items'] as $items) {
                $item = Item::findorfail($items['item_id']);
                $item->update([
                    'quantity' => $item->quantity - $items['quantity'] * $sale['quantity'],
                ]);
                logger($item);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
