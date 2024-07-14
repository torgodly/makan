<?php

namespace App\Models;

use App\Forms\Components\ViewPrice;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'status', 'user_id', 'order_number', 'note', 'discount', 'payment_method'];


    //customer

    public static function Table()
    {
        return [
            TextColumn::make('order_number')
                ->label('Order Number')
                ->translateLabel()
                ->searchable()
                ->sortable(),
            TextColumn::make('customer.name')
                ->label('Customer')
                ->translateLabel()
                ->sortable(),
            TextColumn::make('total_price')
                ->label('Total Price')
                ->translateLabel()
                ->suffix(' د.ل')
                ->sortable(),
            TextColumn::make('status')
                ->label('Status')
                ->translateLabel()
                ->badge()
                ->color(fn($record) => $record->status === 'Open' ? 'green' : 'yellow')
                ->searchable(),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function InfoList()
    {
        return [
            \Filament\Infolists\Components\Group::make([
                \Filament\Infolists\Components\Section::make()->schema([
                    \Filament\Infolists\Components\Grid::make()->schema([
                        TextEntry::make('customer.name')
                            ->translateLabel()
                            ->icon('heroicon-s-user'),
                        TextEntry::make('order_number')
                            ->label('Order Number')
                            ->translateLabel()
                            ->icon('tabler-receipt-2'),
                        TextEntry::make('user.name')
                            ->translateLabel()
                            ->icon('tabler-user'),
                        TextEntry::make('status')
                            ->translateLabel()
                            ->icon('tabler-circle-check')
                            ->badge()
                            ->action(\Filament\Infolists\Components\Actions\Action::make('Change Status')
                                ->requiresConfirmation()
                                ->modalIcon('heroicon-m-pencil')
                                ->translateLabel()
                                ->icon('heroicon-m-pencil')
                                ->hiddenLabel()
                                ->button()
                                ->fillForm(fn($record) => ['status' => $record->status])
                                ->form([
                                    \Filament\Forms\Components\Select::make('status')
                                        ->translateLabel()
                                        ->options([
                                            'Open' => 'Open',
                                            'Finished' => 'Finished',
                                        ])
                                        ->required()
                                ])
                                ->action(function (array $data, Order $record) {
                                    if ($record->payment_need_to_be_paid > 0 && $data['status'] === 'Finished') {
                                        Notification::make()
                                            ->danger()
                                            ->title(__('Payment Required'))
                                            ->icon('tabler-credit-card')
                                            ->body(__('The order cannot be finished until the payment is completed.'))
                                            ->send();
                                        return;
                                    }
                                    $record->update($data);
                                    Notification::make()
                                        ->title(__('Status Changed'))
                                        ->icon('heroicon-m-check')
                                        ->iconColor('green')
                                        ->body(__('The status has been changed successfully.'))
                                        ->send();

                                })

                            )
                            ->color(fn($record) => $record->status === 'Open' ? 'green' : 'blue')
                        ,
                    ])->columns(2),
                ]),
                \Filament\Infolists\Components\Section::make()->schema([
                    RepeatableEntry::make('items')
                        ->label('Order Items')
                        ->translateLabel()
                        ->schema([
                            \Filament\Infolists\Components\Grid::make()->schema([
                                TextEntry::make('product.name')
                                    ->label('Product')
                                    ->translateLabel(),
                                TextEntry::make('price')
                                    ->label('Price')
                                    ->translateLabel(),
                                TextEntry::make('quantity')->label('Quantity')->translateLabel(),
                                TextEntry::make('total_price')->default(fn($record) => $record->price * $record->quantity)
                                    ->label('Total Price')
                                    ->translateLabel(),
                            ])->columns(4)
                        ]),
                ]),
                \Filament\Infolists\Components\Section::make()->schema([
                    RepeatableEntry::make('payments')
                        ->label('Order payments')
                        ->translateLabel()
                        ->schema([
                            \Filament\Infolists\Components\Grid::make()->schema([
                                TextEntry::make('payment_method')
                                    ->label('Payment Method')
                                    ->translateLabel(),
                                TextEntry::make('amount')
                                    ->label('Amount')
                                    ->translateLabel(),
                                TextEntry::make('payment_date')
                                    ->label('Payment Date')
                                    ->translateLabel(),
                            ])->columns(3)
                        ]),
                ])
            ])->columnSpan(['lg' => 2]),
            \Filament\Infolists\Components\Group::make([
                \Filament\Infolists\Components\Section::make()->schema([
                    \Filament\Infolists\Components\Grid::make()->schema([
                        TextEntry::make('total_price_d')
                            ->label('Total Price')
                            ->translateLabel()
                            ->icon('heroicon-s-currency-dollar')
                            ->size('lg')
                            ->weight(FontWeight::ExtraBold)
                            ->default(fn($record) => $record->total_price + $record->discount)
                        ,
                        //discount
                        TextEntry::make('discount')
                            ->label('Discount')
                            ->translateLabel()
                            ->icon('heroicon-s-currency-dollar')
                            ->size('lg')
                            ->weight(FontWeight::ExtraBold),
                    ]),

                    \Filament\Infolists\Components\Grid::make()->schema([
                        //total price after discount
                        TextEntry::make('total_price')
                            ->label('Total Price After Discount')
                            ->translateLabel()
                            ->icon('heroicon-s-currency-dollar')
                            ->size('lg')
                            ->weight(FontWeight::ExtraBold),

                        //payment has been paid
                        TextEntry::make('payment_has_been_paid')
                            ->label('Payment Has Been Paid')
                            ->translateLabel()
                            ->icon('heroicon-s-currency-dollar')
                            ->size('lg')
                            ->weight(FontWeight::ExtraBold)
                            ->default(fn($record) => $record->payments->sum('amount'))
                    ])
                ]),
                //print invoice
                \Filament\Infolists\Components\Section::make()->schema([


                    //note
                    TextEntry::make('note')
                        ->label('Note')
                        ->translateLabel()
//                        ->icon('heroicon-s-annotation')
                        ->size('lg')
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


            ])->columnSpan(['lg' => 1]),
        ];
    }

    public static function Form()
    {
        return [
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
                            ->options([
                                'Cash' => 'كاش',
                                'check' => 'شيك',
                                'credit_card' => 'بطاقة ائتمان',
                                'other' => 'أخرى',
                                'Deferred_Payment' => 'دفع اجل', // دفع اجل
                            ])
                            ->required(),
                    ])->columns(2),
                ]),
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
                            TextInput::make('price')
                                ->label('Unit Price')
                                ->translateLabel()
                                ->hidden()

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
                Section::make(__('discount'))->schema([
                    //discount
                    TextInput::make('discount')
                        ->hiddenLabel()
                        ->numeric()
                        ->inputMode('decimal')
                        ->live()
                        ->default(0)
                ]),
                //note
                Section::make(__('Note'))->schema([
                    Textarea::make('note')
                        ->hiddenLabel()
                        ->rows(3)
                ]),

            ])->columnSpan(['lg' => 1]),
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    //items

    public function product()
    {
        return $this->hasManyThrough(Product::class, OrderItem::class, 'product_id', 'id', 'id', 'order_id');
    }

    //product

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    //user

    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
                return $item->quantity * $item->price;
            }) - $this->discount;
    }

    //total_price

    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }

    //payments

    public function getPaymentNeedToBePaidAttribute()
    {
        return round($this->total_price - $this->payments->sum('amount'), 2);
    }

    //payment need to be paid

    public function getPaidAttribute()
    {
        return $this->payments->sum('amount');
    }

    //paid

    public function getMonthlyProductQuantities()
    {
        $results = $this->items()
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('MONTH(created_at) as month')
            )
            ->groupBy('product_id', 'month')
            ->with('product:id,name')
            ->get();

        $productQuantities = [];

        foreach ($results as $result) {
            $productName = $result->product->name;
            if (!isset($productQuantities[$productName])) {
                $productQuantities[$productName] = array_fill(0, 12, 0); // Initialize all months to 0
            }
            $productQuantities[$productName][$result->month - 1] = $result->total_quantity; // -1 for zero-based index
        }

        return $productQuantities;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
