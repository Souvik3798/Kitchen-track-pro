<?php

namespace App\Filament\Pages;

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
                    ->default(now()->subMonth())
                    ->required(),
                DateTimePicker::make('endDate')
                    ->label('End Date')
                    ->default(now())
                    ->required(),
            ])->columns(2),

        ]);
    }
}
