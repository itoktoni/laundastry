<?php

namespace App\Http\Controllers;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Transaksi;
use App\Http\Controllers\Core\ReportController;
use App\Http\Requests\Core\ReportRequest;
use Plugins\Query;
use Carbon\CarbonPeriod;

class ReportRekapPengirimanRejectController extends ReportController
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
        ->where(Transaksi::field_status(), TransactionType::REJECT)
        ->where(Transaksi::field_bersih(), '>=', 1)
        ->orderBy(Customer::field_name(), 'ASC')
        ->orderBy(Jenis::field_name(), 'ASC')
        ->filter()
        ->get();

        return $query;
    }

    public function getPrint(ReportRequest $request)
    {
        set_time_limit(0);
        $this->data = $this->getData($request);
        $model = $this->data->first();

        $tanggal = CarbonPeriod::create(request('start_date'), request('end_date'));
        $jenis = $this->data->sortBy('jenis_nama')->pluck(Jenis::field_name(), Jenis::field_primary());

        return moduleView(modulePathPrint(), $this->share([
            'data' => $this->data,
            'jenis' => $jenis,
            'model' => $model,
            'tanggal' => $tanggal,
        ]));
    }
}
