<?php

namespace App\Http\Controllers;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Transaksi;
use App\Http\Controllers\Core\ReportController;
use Illuminate\Http\Request;
use Plugins\Query;

class ReportDetailPenerimaanKotorController extends ReportController
{
    public $data;

    protected function share($data = [])
    {
        $customer = Query::getCustomerByUser();
        $jenis = [];

        if(request()->has('customer_code'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer_code'));
        }

        $view = [
            'jenis' => $jenis,
            'model' => $this->model,
            'customer' => $customer,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function getData()
    {
        $query = Transaksi::select([
            Transaksi::getTableName().'.*',
            Customer::field_name(),
            Jenis::field_name()
        ])
        ->leftJoinRelationship('has_customer')
        ->leftJoinRelationship('has_jenis')
        ->where(Transaksi::field_status(), TransactionType::KOTOR)
        ->orderBy(Customer::field_name(), 'ASC')
        ->orderBy(Jenis::field_name(), 'ASC')
        ->filter()
        ->get();

        return $query;
    }

    public function getPrint(Request $request)
    {
        set_time_limit(0);
        $this->data = $this->getData($request);
        $model = $this->data->first();
        $customer = Customer::find($request->get('customer_code'));

        return moduleView(modulePathPrint(), $this->share([
            'data' => $this->data,
            'model' => $model,
            'customer' => $customer,
        ]));
    }
}
