<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Resources\InventoryResource;
use App\Models\inventory;
use App\Models\Item;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInventory extends CreateRecord
{
    protected static string $resource = InventoryResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    protected function afterCreate()
    {
        foreach ($this->record->item as $item) {
            $itemModel = Item::find($item['item_id']);
            $itemModel->update([
                'quantity' => $itemModel->quantity + $item['quantity']
            ]);
            // dd($itemModel);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
