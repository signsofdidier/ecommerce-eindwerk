<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('tenant_route')) {
    function tenant_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        $company = request()->route('company');

        if ($company) {
            $parameters = array_merge(['company' => $company], $parameters);
            return route("company.$name", $parameters, $absolute);
        }

        return route($name, $parameters, $absolute);
    }
}
