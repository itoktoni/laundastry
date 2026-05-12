<?php

namespace App\Http\Controllers;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Transaksi;
use App\Http\Controllers\Core\ReportController;
use Plugins\Query;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class ReportRekapPendingKotorController extends ReportController
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
            Customer::field_name(),
            Jenis::field_primary(),
            Jenis::field_name()
        ])
        ->leftJoinRelationship('has_customer')
        ->leftJoinRelationship('has_jenis')
        ->whereNotNull(Transaksi::field_report())
        ->where(Transaksi::field_pending(), '>=', 1)
        ->where(Transaksi::field_status(), TransactionType::KOTOR)
        ->orderBy(Customer::field_name(), 'ASC')
        ->orderBy(Jenis::field_name(), 'ASC');

        if($customer = request('customer_code'))
        {
            $query = $query->where('transaksi_code_customer', $customer);
        }

        return $query->filter()->get();
    }

    public function getPrint(Request $request)
    {
        set_time_limit(0);
        $this->data = $this->getData($request);
        $model = $this->data->first();

        $tanggal = CarbonPeriod::create(request('start_date'), request('end_date'));
        $jenis = $this->data->sortBy('jenis_nama')->pluck(Jenis::field_name(), Jenis::field_primary());
        $customer = Customer::find(request()->get('customer_code'));

        return moduleView(modulePathPrint(), $this->share([
            'data' => $this->data,
            'jenis' => $jenis,
            'model' => $model,
            'customer' => $customer,
            'tanggal' => $tanggal,
        ]));
    }
}
