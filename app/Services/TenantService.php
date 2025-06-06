<?php

namespace App\Services;

use App\Models\Company;
use Filament\Facades\Filament;

class TenantService
{
    public static function current()
    {
        return Filament::getTenant();
    }

    public static function id(): ?int
    {
        return self::current()?->id;
    }

    public static function slug(): ?string
    {
        return request()->route('company') ?? optional(self::current())->slug;
    }




}
