<?php

if (!function_exists('tenant')) {
    function tenant()
    {
        return config('app.current_tenant');
    }
}
