<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    public static function getModelLabel(): string
    {
        return __('Order');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Orders');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Order::Form())->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(Order::Table())
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->translateLabel()
                    ->native(false)
                    ->options([
                        'Open' => 'Open',
                        'Finished' => 'Finished',
                    ]),
                Tables\Filters\SelectFilter::make('customer_id')
                    ->label('Customer')
                    ->translateLabel()
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->translateLabel()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('Print Invoice')
                    ->translateLabel()
                    ->color('secondary')
                    ->icon('heroicon-o-printer')
                    ->url(fn(Order $order) => route('print', $order)),
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(Order::InfoList())->columns(3);
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
