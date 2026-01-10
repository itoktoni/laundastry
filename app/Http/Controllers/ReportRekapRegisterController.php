<?php

namespace App\Http\Controllers;

use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Register;
use App\Http\Controllers\Core\ReportController;
use App\Http\Requests\Core\ReportRequest;
use Plugins\Query;
use Carbon\CarbonPeriod;

class ReportRekapRegisterController extends ReportController
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
        $query = Register::select([
            Register::getTableName().'.*',
            Customer::field_name(),
            Jenis::field_primary(),
            Jenis::field_name()
        ])
        ->leftJoinRelationship('has_customer')
        ->leftJoinRelationship('has_jenis')
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
        $customer = Customer::find($request->get('customer_code'));

        return moduleView(modulePathPrint(), $this->share([
            'data' => $this->data,
            'jenis' => $jenis,
            'model' => $model,
            'customer' => $customer,
            'tanggal' => $tanggal,
        ]));
    }
}
