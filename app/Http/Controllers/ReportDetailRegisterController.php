<?php

namespace App\Http\Controllers;

use App\Dao\Models\Core\User;
use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Register;
use App\Http\Controllers\Core\ReportController;
use Illuminate\Http\Request;
use Plugins\Query;

class ReportDetailRegisterController extends ReportController
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
        $query = Register::select([
            Register::getTableName().'.*',
            Customer::field_name(),
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

    public function getPrint(Request $request)
    {
        set_time_limit(0);
        $this->data = $this->getData($request);

        return moduleView(modulePathPrint(), $this->share([
            'data' => $this->data,
        ]));
    }
}
