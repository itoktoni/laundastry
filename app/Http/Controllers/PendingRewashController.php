<?php

namespace App\Http\Controllers;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Pending;
use App\Dao\Models\Transaksi;

class PendingRewashController extends PendingController
{
    public function __construct(Transaksi $model)
    {
        $this->model = $model::getModel();
        $this->type = TransactionType::REWASH;
    }

    public function getData()
    {
        $query = Pending::query()
            ->where(Transaksi::field_status(), $this->type)
            ->where($this->model->field_pending(),'>=', 1)
            ->whereColumn('transaksi_pending', '>', 'transaksi_bayar')
            ->filter();

        $per_page = env('PAGINATION_NUMBER', 10);
        if(request()->get('per_page'))
        {
            $per_page = request()->get('per_page');
        }

        $query = env('PAGINATION_SIMPLE') ? $query->simplePaginate($per_page) : $query->fastPaginate($per_page);

        return $query;
    }
}
