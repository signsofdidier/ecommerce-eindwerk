<?php

if (!function_exists('tenant')) {

    // haalt de huidige tenant op
    function tenant()
    {
        return config('app.current_tenant');
    }

    // zorgt dat we {{ tenant_url('naam') }} kunnen gebruiken
    function tenant_url(string $path = ''): string {
        return url(trim(request()->route('company') . '/' . ltrim($path, '/'), '/'));
    }

    // zorgt dat we {{ tenant_storage_path('naam') }} kunnen gebruiken
    function tenant_storage_path($path = ''): string {
        $tenantId = tenant()?->id ?? 'global'; // fallback indien geen tenant
        return "tenants/{$tenantId}/" . ltrim($path, '/');
    }

}
