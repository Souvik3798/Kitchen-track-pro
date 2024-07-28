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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                            ->suffix('Nos.')
                            ->numeric()
                            ->required(),
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
                    ->dateTime(),
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
