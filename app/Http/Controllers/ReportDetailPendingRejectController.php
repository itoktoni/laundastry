<?php

namespace App\Http\Controllers;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Core\User;
use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\PendingDetail;
use App\Dao\Models\Transaksi;
use App\Http\Controllers\Core\ReportController;
use Illuminate\Http\Request;
use Plugins\Query;

class ReportDetailPendingRejectController extends ReportController
{
    public $data;

    protected function share($data = [])
    {
        $customer = Query::getCustomerByUser();
        $jenis = [];

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }
        else{
            $jenis = Query::getJenisByCustomerCode($customer->keys());
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
            PendingDetail::getTableName().'.*',
            Customer::field_name(),
            User::field_name(),
            Jenis::field_name()
        ])
        ->leftJoinRelationship('has_detail')
        ->leftJoinRelationship('has_detail.has_user')
        ->leftJoinRelationship('has_customer')
        ->leftJoinRelationship('has_jenis')
        ->whereNotNull(Transaksi::field_report())
        ->where(Transaksi::field_status(), TransactionType::REJECT)
        ->where(Transaksi::field_pending(), '>=', 1)
        ->orderBy(Customer::field_name(), 'ASC')
        ->orderBy(Jenis::field_name(), 'ASC');

        if($customer = request('customer_code'))
        {
            $query = $query->where('transaksi_code_customer', $customer);
        }

        if($start_date = request('start_date'))
        {
            $query = $query->where('transaksi_report', '>=', $start_date);
        }

        if($end_date = request('end_date'))
        {
            $query = $query->where('transaksi_report', '<=', $end_date);
        }

        return $query->get();
    }

    public function getPrint(Request $request)
    {
        set_time_limit(0);
        $this->data = $this->getData($request);
        $customer = Customer::find($request->get('customer_code'));
        $model = $this->data->first();

        return moduleView(modulePathPrint(), $this->share([
            'data' => $this->data->groupBy('jenis_nama'),
            'customer' => $customer,
            'model' => $model
        ]));
    }
}
