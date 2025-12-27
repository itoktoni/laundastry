<?php

namespace App\Http\Controllers;

use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Opname;
use App\Dao\Models\OpnameDetail;
use App\Dao\Models\Register;
use App\Dao\Models\Transaksi;
use App\Http\Controllers\Core\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Plugins\Query;

class ReportDataOpnameController extends ReportController
{
    public $data;

    protected function share($data = [])
    {
        $opname = Query::getOpnameByUser();
        $jenis = [];

        if(request()->has('customer'))
        {
            $customer = Opname::find(request('customer'));
            $jenis = Query::getJenisByCustomerCode($customer->opname_code_customer);
        }

        $view = [
            'jenis' => $jenis,
            'model' => $this->model,
            'opname' => $opname,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function getData()
    {
        $query = DB::table('view_opname')->where('odetail_id_opname', request('opname'));

        if($jenis = request('jenis_id'))
        {
            $query = $query->where('jenis_id', $jenis);
        }

        return $query->get();
    }

    public function getPrint(Request $request)
    {
        set_time_limit(0);
        $this->data = $this->getData($request);

        $detail = $this->data->mapToGroups(function($item){
            return [$item->jenis_id => $item];
        });

        $model = Opname::with('has_customer')->find(request('opname'));

        return moduleView(modulePathPrint(), $this->share([
            'data' => $this->data,
            'model' => $model,
            'detail' => $detail
        ]));
    }
}
