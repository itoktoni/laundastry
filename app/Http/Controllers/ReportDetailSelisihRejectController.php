<?php

namespace App\Http\Controllers;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Core\User;
use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Transaksi;
use App\Http\Controllers\Core\ReportController;
use Illuminate\Http\Request;
use Plugins\Query;

class ReportDetailSelisihRejectController extends ReportController
{
    public $data;

    protected function share($data = [])
    {
        $customer = Query::getCustomerByUser();
        $jenis = [];

        $lokasi = [];

        if(request()->has('customer_code'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer_code'));
            $lokasi = Query::getLokasiByCustomerCode(request()->get('customer_code'));
        }
        else
        {
            $jenis = Query::getJenisByCustomerCode($customer->keys());
            $lokasi = Query::getLokasiByCustomerCode($customer->keys());
        }

        $view = [
            'jenis' => $jenis,
            'lokasi' => $lokasi,
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
            User::field_name(),
            Jenis::field_name()
        ])
        ->leftJoinRelationship('has_customer')
        ->leftJoinRelationship('has_created')
        ->leftJoinRelationship('has_lokasi')
        ->leftJoinRelationship('has_jenis')
        ->where(Transaksi::field_status(), TransactionType::REJECT)
        ->where(Transaksi::field_scan(), '>', 0)
        ->orderBy(Customer::field_name(), 'ASC')
        ->orderBy(Jenis::field_name(), 'ASC')
        ->filter();

        if($lokasi = request('lokasi'))
        {
            $query->whereIn('transaksi_id_lokasi', $lokasi);
        }

        if($customer = request('customer_code'))
        {
            $query = $query->where('transaksi_code_customer', $customer);
        }

        if($start_date = request('start_date'))
        {
            $query = $query->where('transaksi_tanggal', '>=', $start_date);
        }

        if($end_date = request('end_date'))
        {
            $query = $query->where('transaksi_tanggal', '<=', $end_date);
        }

        return $query->get();
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
