<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Sales  today
        $salesToday = Order::whereDate('created_at', today())->count();
        //cash register balance today
        $cashRegisterBalanceToday = OrderPayment::whereDate('created_at', today())->sum('amount');

        //top products  name sold
        $topProducts = OrderItem::selectRaw('product_id, count(*) as count')
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->limit(1)
            ->get();

        return [
            Stat::make(__('Sales today'), $salesToday)
                ->color('primary')
                ->description(__('Total Sales today'))
                ->descriptionIcon('tabler-shopping-cart')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            Stat::make(__('Cash register balance today'), $cashRegisterBalanceToday)
                ->color('green')
                ->description(__('Total Cash register balance today'))
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->chart([3, 2, 5, 1, 6, 3, 7]),
            Stat::make(__('Top product'), $topProducts->first()?->product->name ?? 'No product')
                ->color('yellow')
                ->description(__('Top product sold'))
                ->descriptionIcon('tabler-package')
                ->chart([1, 2, 3, 4, 5, 6, 7]),
        ];
    }
}
