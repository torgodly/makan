<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Forms\Components\ViewPrice;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->schema([
                Group::make([
                    Section::make(__('Customer Info'))->schema([
                        Grid::make()->schema([
                            Select::make('customer_id')
                                ->translateLabel()
                                ->label('Customer')
                                ->relationship('customer', 'name')
                                ->preload()
                                ->suffixAction(Action::make('Add Customer')
                                    ->icon('heroicon-m-plus')
                                    ->requiresConfirmation()
                                    ->form(Customer::Form())
                                    ->action(function (array $data) {
                                        Customer::create($data);
                                        Notification::make()
                                            ->title(__('Customer Added'))
                                            ->icon('heroicon-m-check')
                                            ->iconColor('green')
                                            ->body(__('The customer has been added successfully.'))
                                            ->send();
                                    }))
                                ->searchable()
                                ->required(),

                            Select::make('payment_method')
                                ->suffixIcon('tabler-credit-card')
                                ->translateLabel()
                                ->options(['Cash', 'Credit Card', 'Bank Transfer'])
                                ->required(),
                        ])->columns(2),
                    ]),
//                    Section::make(__('Note'))->schema([
//                        RichEditor::make('note')
//                    ]),
                    Section::make(__('Items'))->schema([
                        Repeater::make('items')
                            ->translateLabel()
                            ->relationship('items')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->translateLabel()
                                    ->options(Product::all()->pluck('name', 'id')->toArray())
                                    ->afterStateHydrated(fn(Set $set, Get $get) => $set('price', Product::find($get('product_id'))?->price))
                                    ->afterStateUpdated(fn(Set $set, Get $get) => $set('price', Product::find($get('product_id'))?->price))
                                    ->live()
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                TextInput::make('price')
                                    ->label('Unit Price')
                                    ->translateLabel()
                                    ->live()
                                    ->readOnly()
//                                    ->disabled()
                                ,
                                TextInput::make('quantity')
                                    ->translateLabel()
                                    ->required()
                                    ->default(1)
                                    ->numeric()
                                    ->integer()
                                    ->live()
                            ])->columns(3)
                    ])
                ])->columnSpan(['lg' => 2]),
                Group::make([
                    Section::make(__('Pricing'))->schema([
                        ViewPrice::make('total_price')
                            ->hiddenLabel()
                            ->disabled(),
                    ]),
//                    Section::make(__('Car Details'))->schema([
//                        Grid::make()->schema([])->columns(2),
//                    ]),

                ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($record) => $record->status === 'Open' ? 'green' : 'yellow')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            \Filament\Infolists\Components\Group::make([
                \Filament\Infolists\Components\Section::make()->schema([
                    \Filament\Infolists\Components\Grid::make()->schema([
                        TextEntry::make('customer.name')
                            ->icon('heroicon-s-user'),
                        TextEntry::make('order_number')
                            ->icon('tabler-receipt-2'),
                        TextEntry::make('user.name')
                            ->icon('tabler-user'),
                        TextEntry::make('payment_method')
                            ->icon('tabler-credit-card')
                            ->badge(),
                        TextEntry::make('status')
                            ->icon('tabler-circle-check')
                            ->badge()
                            ->color(fn($record) => $record->status === 'Open' ? 'green' : 'yellow')
                        ,
                    ])->columns(2),
                ]),
//                \Filament\Infolists\Components\Section::make()->schema([
//                    TextEntry::make('note')
//                ]),
                \Filament\Infolists\Components\Section::make()->schema([
                    RepeatableEntry::make('items')
                        ->schema([
                            \Filament\Infolists\Components\Grid::make()->schema([
                                TextEntry::make('product.name')
                                    ->label('Product')
                                    ->translateLabel(),
                                TextEntry::make('product.price')
                                    ->label('Price')
                                    ->translateLabel(),
                                TextEntry::make('quantity')->label('Quantity')->translateLabel(),
                                TextEntry::make('total_price')->default(fn($record) => $record->product->price * $record->quantity)
                                    ->label('Total Price')
                                    ->translateLabel(),
                            ])->columns(4)
                        ])

                ])
            ])->columnSpan(['lg' => 2]),
            \Filament\Infolists\Components\Group::make([
                \Filament\Infolists\Components\Section::make()->schema([
                    TextEntry::make('total_price')
                        ->label('Total Price')
                        ->translateLabel()
                        ->icon('heroicon-s-currency-dollar')
                        ->size('lg')
                        ->weight(FontWeight::ExtraBold)
                ]),
                \Filament\Infolists\Components\Section::make()->schema([
                    TextEntry::make('created_at')
                        ->label('Created At')
                        ->translateLabel()
                        ->icon('heroicon-s-calendar')
//                        ->size('lg')
                    ,
                    TextEntry::make('updated_at')
                        ->label('Updated At')
                        ->translateLabel()
                        ->icon('heroicon-s-calendar')
//                        ->size('lg')
                    ,

                ]),

                //print invoice
                \Filament\Infolists\Components\Section::make()->schema([
                ]),
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
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
