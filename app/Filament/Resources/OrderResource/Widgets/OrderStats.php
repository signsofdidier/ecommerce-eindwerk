<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $avgPrice = Order::query()->avg('grand_total');

        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            Stat::make('Order Processing', Order::query()->where('status', 'processing')->count()),
            Stat::make('Order Shipped', Order::query()->where('status', 'shipped')->count()),

            Stat::make(
                'Average Price',
                $avgPrice ? Number::currency($avgPrice, 'EUR') : 'No orders yet'
            )
                ->description($avgPrice ? 'Average order value' : '')
                ->color($avgPrice ? 'success' : 'gray'),
        ];
    }
}
