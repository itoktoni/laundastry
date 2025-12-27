<?php

namespace App\Models\Scopes;

use App\Dao\Models\Transaksi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Plugins\Query;

class OpnameScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $filter = Query::getCustomerByRole();
        if($filter)
        {
            $builder->whereIn('opname_code_customer', $filter);
        }
    }
}
