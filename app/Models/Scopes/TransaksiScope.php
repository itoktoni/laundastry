<?php

namespace App\Models\Scopes;

use App\Dao\Models\Transaksi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Plugins\Query;

class TransaksiScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        dd(true);
        $filter = Query::getCustomerByRole();
        if($filter)
        {
            $builder->whereIn(Transaksi::field_customer_code(), $filter);
        }

        if (request()->has('customer_code')) {
            $builder->where('transaksi_code_customer', request()->get('customer_code'));
        }
    }
}
