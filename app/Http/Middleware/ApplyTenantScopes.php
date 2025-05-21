<?php

namespace App\Http\Middleware;

use App\Models\Address;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Filament::getTenant();

        if ($tenant) {
            Category::addGlobalScope('tenant', function (Builder $query) use ($tenant) {
                $query->where('company_id', $tenant->id);
            });

            Product::addGlobalScope('tenant', function (Builder $query) use ($tenant) {
                $query->where('company_id', $tenant->id);
            });

            Order::addGlobalScope('tenant', function (Builder $query) use ($tenant) {
                $query->where('company_id', $tenant->id);
            });

            OrderItem::addGlobalScope('tenant', function (Builder $query) use ($tenant) {
                $query->where('company_id', $tenant->id);
            });

            Brand::addGlobalScope('tenant', function (Builder $query) use ($tenant) {
                $query->where('company_id', $tenant->id);
            });

            Address::addGlobalScope('tenant', function (Builder $query) use ($tenant) {
                $query->where('company_id', $tenant->id);
            });
        }

        return $next($request);
    }
}
