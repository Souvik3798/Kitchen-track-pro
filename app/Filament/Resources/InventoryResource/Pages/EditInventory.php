<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Resources\InventoryResource;
use App\Models\inventory;
use App\Models\Item;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditInventory extends EditRecord
{
    protected static string $resource = InventoryResource::class;

    protected $previousItemData;

    protected function beforeSave()
    {
        $this->previousItemData = $this->record->item;
        logger($this->previousItemData);
    }

    protected function afterSave()
    {
        $items = $this->record->item;
        logger($items);

        // Update quantities for updated items
        foreach ($items as $index => $item) {
            $existingItem = Item::findorfail($item['item_id']);
            logger($existingItem);

            $previousQuantity = $this->previousItemData[$index]['quantity'] ?? 0;

            $existingItem->update([
                'quantity' => $existingItem->quantity + ($item['quantity'] - $previousQuantity),
            ]);
            logger($existingItem);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
