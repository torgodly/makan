<?php

namespace App\Filament\Reports;

use App\Models\OrderItem;
use App\Models\User;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Report;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;

class ProfitReport extends Report
{
    public ?string $heading = "Report";

     public static function getNavigationLabel(): string
     {
         return __("Profit Report");
     }


    public function getHeading(): string
    {
        return __("Profit Report");
    }


    // public ?string $subHeading = "A great report";

    public function header(Header $header): Header
    {
        return $header
            ->schema([
                Header\Layout\HeaderRow::make()
                    ->schema([
                        Header\Layout\HeaderColumn::make()
                            ->alignRight()
                            ->schema([
                                Text::make(__("Profit Report"))
                                    ->title()
                                    ->primary(),
                                Text::make(__("This report shows the profit of each product."))
                                    ->subtitle(),
                            ]),
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                            ])
                            ->alignRight(),
                    ]),]);
    }


    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Body\Layout\BodyColumn::make()
                    ->schema([
                        Body\Table::make()
                            ->columns([
                                Body\TextColumn::make("product_name")
                                    ->label("Product Name")
                                    ->translateLabel(),
                                Body\TextColumn::make("total_sold")
                                    ->label("Total Sold")
                                    ->translateLabel()
                                    ->sum(),
                                Body\TextColumn::make("cost")
                                    ->suffix(" د.ل")
                                    ->label("Total Cost")
                                    ->translateLabel()
                                    ->sum(),
                                Body\TextColumn::make("price")
                                    ->suffix(" د.ل")
                                    ->label("Total Price")
                                    ->translateLabel()
                                    ->sum(),
                            ])
                            ->data(
                                fn(?array $filters) => $this->getData($filters)
                            ),
                    ]),
            ]);
    }

    private function getData(?array $filters): Collection
    {
        // Get the date range from filters
        [$from, $to, $range] = getCarbonInstancesFromDateString($filters["created_at"] ?? null);

        // Query to get the order items data with the applied date range filter
        $orderItems = OrderItem::select([
            'product_id',
            DB::raw('SUM(quantity) as total_quantity'),
            DB::raw('SUM(order_items.quantity * products.cost) as total_cost'),
            DB::raw('SUM(order_items.quantity * products.price) as total_price')
        ])
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            // Apply the date range filter to the orders.created_at field

            ->groupBy('product_id')
            ->with('product') // eager load product details
            ->get();

        // Map the result to the desired format
        return $orderItems->map(function ($orderItem) {
            return [
                'product_name' => $orderItem->product->name,
                'total_sold' => $orderItem->total_quantity,
                'cost' => $orderItem->total_cost,
                'price' => $orderItem->total_price,
                'total_price' => $orderItem->total_price
            ];
        });
    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                // ...
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([

                DateRangePicker::make("created_at")
                    ->label("Date Range")
                    ->translateLabel()
                    ->defaultToday()
                    ->placeholder("Select a date range")
            ]);
    }

}
