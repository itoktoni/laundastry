<?php

namespace App\Http\Controllers;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Kotor;
use App\Dao\Models\Transaksi;

class RejectController extends TransaksiController
{
    public $type = TransactionType::REJECT;

    public function getData()
    {
        $query = Kotor::where(Transaksi::field_status(), $this->type)
            ->active()
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
