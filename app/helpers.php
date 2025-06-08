<?php

if (!function_exists('tenant')) {
    function tenant()
    {
        return config('app.current_tenant');
    }

    function tenant_url(string $path = ''): string {
        return url(trim(request()->route('company') . '/' . ltrim($path, '/'), '/'));
    }
}
