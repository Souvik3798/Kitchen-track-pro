<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DishResource\Pages;
use App\Filament\Resources\DishResource\RelationManagers;
use App\Models\dish;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DishResource extends Resource
{
    protected static ?string $model = dish::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Hidden::make('user_id')
                    ->default(auth()->id()),
                Repeater::make('items')
                    ->schema([
                        Select::make('item_id')
                            ->label('Item')
                            ->options(Item::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->required(),
                        TextInput::make('quantity')
                            ->required()
                            ->suffix('grams/Nos/ml')
                            ->placeholder('2kg = 2000grams')
                            ->label('Quantity')
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
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Updated On')
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
            'index' => Pages\ListDishes::route('/'),
            'create' => Pages\CreateDish::route('/create'),
            'edit' => Pages\EditDish::route('/{record}/edit'),
        ];
    }
}
