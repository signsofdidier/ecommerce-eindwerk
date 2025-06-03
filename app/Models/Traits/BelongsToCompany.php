<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

/**
 * Trait BelongsToCompany
 *
 * Voeg deze trait toe aan elk “tenant‐gebonden” model.
 * - Bij queries (zoals Model::all(), Model::where(...)) voegt het automatisch "WHERE company_id = {currentCompany}" toe.
 * - Bij creating vult het automatisch $model->company_id = {currentCompany}.
 *
 * Vereist dat er ergens (in middleware) een instantie van het huidige Company‐model onder de key 'currentCompany' in de container staat.
 */
trait BelongsToCompany
{
    public static function bootBelongsToCompany()
    {
        // Global scope: filter automatisch op company_id
        static::addGlobalScope('company_id', function (Builder $builder) {
            if (App::bound('currentCompany')) {
                $current = App::make('currentCompany');
                $builder->where($builder->getModel()->getTable() . '.company_id', $current->id);
            }
        });

        // Bij create: vul automatisch company_id in
        static::creating(function (Model $model) {
            if (App::bound('currentCompany')) {
                $current = App::make('currentCompany');
                $model->company_id = $current->id;
            }
        });
    }

    /**
     * Helper‐relatie: Model behoort tot één Company.
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }
}
