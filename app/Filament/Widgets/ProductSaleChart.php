<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class ProductSaleChart extends ChartWidget
{
    protected static ?string $heading = 'Product Sale Chart';


    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 2;



    protected function getData(): array
    {

        //from product order get the items ans sum the quantity and return the data set for each product how many times it has been sold


        $orders = Order::all();
        $productQuantities = [];

        foreach ($orders as $order) {
            $monthlyQuantities = $order->getMonthlyProductQuantities();
            foreach ($monthlyQuantities as $product => $quantities) {
                if (isset($productQuantities[$product])) {
                    for ($i = 0; $i < 12; $i++) {
                        $productQuantities[$product][$i] += $quantities[$i];
                    }
                } else {
                    $productQuantities[$product] = $quantities;
                }
            }
        }

        $datasets = [];
        foreach ($productQuantities as $product => $quantities) {
            $datasets[] = [
                'label' => $product,
                'data' => $quantities,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
