<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use App\Models\dish;
use App\Models\inventory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->action('deleteItem'),
        ];
    }

    public function deleteItem()
    {
        $item = $this->record;
        // dd($item->id);

        $inventories = inventory::all();
        foreach ($inventories as $inventory) {
            foreach ($inventory->item as $items) {
                // dd($items);
                if ($item->id == $items['item_id']) {
                    Notification::make()
                        ->title('Cannot able to Delete')
                        ->body('Item exists in Inventory')
                        ->color('danger')
                        ->danger()
                        ->send();
                    return;
                }
            }
        }


        // Delete the item
        $item->delete();

        // Redirect to the index page
        $this->redirect(route('filament.admin.resources.items.index'));
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
