<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Models\inventory;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('supplier')
                    ->label('Supplier Name')
                    ->required(),
                Hidden::make('user_id')
                    ->default(auth()->id()),
                Repeater::make('item')
                    ->schema([
                        Select::make('item_id')
                            ->label('Item Name')
                            // ->options(Item::pluck('name', 'id')->toArray())
                            ->relationship('item', 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Hidden::make('user_id')
                                    ->default(auth()->id()),
                                Hidden::make('quantity')
                                    ->default('0.00'),
                            ])
                            ->preload()
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Quantity')
                            ->suffix('grams/Nos/ml')
                            ->placeholder('2kg = 2 x 1000grams = 2000grams')
                            ->numeric() // Ensures the input is numeric
                            ->step(0.01)
                            ->required()
                            ->default(0.00)
                            ->afterStateUpdated(function ($state, $set) {
                                // Format the input to ensure it always has a leading zero
                                if (is_numeric($state)) {
                                    $set('quantity', number_format((float)$state, 2, '.', ''));
                                }
                            }),
                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->default('0'),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier')
                    ->label('Supplier Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_count')
                    ->label('Number of Items')
                    ->getStateUsing(fn (Inventory $record) => $record->item_count)
                    ->suffix(' Nos.'),
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}
