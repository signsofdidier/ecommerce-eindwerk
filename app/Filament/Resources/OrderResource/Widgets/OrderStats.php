<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $tenantId = Filament::getTenant()->id;

        return [
            Stat::make('New Orders',
                Order::query()
                    ->where('company_id', $tenantId) // stats enkel van de company
                    ->where('status', 'new')
                    ->count()
            ),
            Stat::make('Order Processing',
                Order::query()
                    ->where('company_id', $tenantId)
                    ->where('status', 'processing')
                    ->count()
            ),
            Stat::make('Order Shipped',
                Order::query()
                    ->where('company_id', $tenantId)
                    ->where('status', 'shipped')
                    ->count()
            ),
            Stat::make('Average Price',
                Number::currency(
                    Order::query()
                        ->where('company_id', $tenantId)
                        ->avg('grand_total') ?? 0,
                    'EUR'
                )
            ),
        ];
    }
}
