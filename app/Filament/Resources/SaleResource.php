<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SaleResource\Pages;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\dish;
use App\Models\sale;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class SaleResource extends Resource
{
    protected static ?string $model = sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('customer')
                    ->required()
                    ->maxLength(255),
                Hidden::make('user_id')
                    ->default(auth()->id()),
                Repeater::make('dish')
                    ->schema([
                        Select::make('dish_id')
                            ->label('Dish')
                            ->options(dish::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->suffix('grams/Nos./ml')
                            ->numeric() // Ensures the input is numeric
                            ->step(0.01)
                            ->required()
                            ->default(0.00)
                            ->afterStateUpdated(function ($state, $set, Get $get) {
                                // Format the input to ensure it always has a leading zero
                                if (is_numeric($state)) {
                                    $set('quantity', number_format((float)$state, 2, '.', ''));
                                }

                                $dish = dish::findorfail($get('dish_id'));
                                Log::error($dish);
                                if ($dish) {
                                    $price = $dish->price;
                                    $set('price', number_format($price * $state, 2, '.', ''));
                                }
                            }),
                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->prefix('₹.')
                            ->suffix('/-')
                            ->default('0'),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dish_count')
                    ->label('Number of Dishes')
                    ->getStateUsing(fn (Sale $record) => count($record->dish))
                    ->searchable()
                    ->suffix(' Nos.')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Updated On')
                    ->searchable()
                    ->sortable()
                    ->dateTime('M j, Y-h:i A'),
            ])->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
