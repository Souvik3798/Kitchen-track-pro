<?php

namespace App\Filament\Resources\DishResource\Pages;

use App\Filament\Resources\DishResource;
use App\Models\sale;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDish extends EditRecord
{
    protected static string $resource = DishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function deleteItem()
    {
        $dish = $this->record;
        // dd($item->id);

        $sales = sale::all();
        foreach ($sales as $sale) {
            foreach ($sale->dish as $dishes) {
                // dd($items);
                if ($dish->id == $dishes['dish_id']) {
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
        $dish->delete();

        // Redirect to the index page
        $this->redirect(route('filament.admin.resources.dishes.index'));
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
