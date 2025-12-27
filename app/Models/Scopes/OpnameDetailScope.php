<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Plugins\Query;

class OpnameDetailScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $filter = Query::getCustomerByRole();
        if($filter)
        {
            $builder->whereIn('odetail_code_customer', $filter);
        }
    }
}
