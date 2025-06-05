<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\Setting;

class CompanyObserver
{
    public function created(Company $company)
    {
        Setting::create([
            'company_id' => $company->id,
            'free_shipping_threshold' => 1000,
        ]);
    }
}
