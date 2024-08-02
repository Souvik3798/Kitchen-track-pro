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
                DatePicker::make('startDate')
                    ->label('Start Date')
                    ->default(now()->subMonth()->format('Y-m-d'))
                    ->required()->afterStateHydrated(function ($state, callable $set) {
                        $set('startDate', Carbon::parse($state)->format('Y-m-d H:i:s'));
                    }),
                DatePicker::make('endDate')
                    ->label('End Date')
                    ->default(now()->format('Y-m-d'))
                    ->required()
                    ->required()->afterStateHydrated(function ($state, callable $set) {
                        $set('startDate', Carbon::parse($state)->format('Y-m-d H:i:s'));
                    }),
            ])->columns(2),

        ]);
    }
}
