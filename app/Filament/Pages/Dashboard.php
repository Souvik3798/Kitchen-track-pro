<?php

namespace App\Filament\Pages;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('')->schema([
                DateTimePicker::make('startDate')
                    ->label('Start Date')
                    ->required()
                    ->reactive() // Make it reactive to changes
                    ->afterStateUpdated(function ($state, callable $set) {
                        $date = Carbon::parse($state);
                        $date->setTime(23, 59, 0); // Set time to 11:59 PM
                        $set('endDate', $date->format('Y-m-d H:i:s'));
                    }),
                DateTimePicker::make('endDate')
                    ->label('End Date')
                    ->default(now())
                    ->required()
                    ->afterStateHydrated(function ($state, callable $set) {
                        $date = Carbon::parse($state);
                        $date->setTime(23, 59, 0); // Set time to 11:59 PM
                        $set('endDate', $date->format('Y-m-d H:i:s'));
                    }),
            ])->columns(2),
        ]);
    }
}
