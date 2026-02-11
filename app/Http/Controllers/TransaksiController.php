<?php

namespace App\Http\Controllers;

use App\Dao\Enums\TransactionType;
use App\Dao\Models\Category;
use App\Dao\Models\Customer;
use App\Dao\Models\Kotor;
use App\Dao\Models\Transaksi;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Http\Requests\TransaksiRequest;
use App\Services\CreateTransaksiService;
use App\Services\Master\SingleService;
use App\Services\UpdateTransaksiService;
use Plugins\Alert;
use Plugins\Notes;
use Plugins\Query;
use Plugins\Response;
use Illuminate\Support\Carbon;

class TransaksiController extends MasterController
{
    use CreateFunction, UpdateFunction;

    public $type = null;

    public function __construct(Transaksi $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    protected function share($data = [])
    {
        $customer = Query::getCustomerByUser();
        $jenis = [];

        if(request()->has('customer') || count($customer) == 1)
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer', convertSingleKeys($customer)));
        }

        $category = Category::getOptions();

        $view = [
            'category' => $category,
            'jenis' => $jenis,
            'model' => $this->model,
            'customer' => $customer,
        ];

        return self::$share = array_merge($view, self::$share, $data);

    }

    public function getTable()
    {
        $data = $this->getData();
        return moduleView(modulePathTable(core: self::$is_core), $this->share([
            'data' => $data,
            'type' => $this->type,
            'fields' => $this->model::getModel()->getShowField(),
        ]));
    }

    public function postTable()
    {
        if (request()->exists('delete'))
        {
            $data = $this->deleteData(request()->get('code'));
        }

        if (request()->exists('sort')) {
            $sort = array_unique(request()->get('sort'));
            $data = self::$service->sort($this->model, $sort);
        }

        return Response::redirectBack($data);
    }

    public function getCreate()
    {
        return moduleView(modulePathForm('form', 'transaksi'), $this->share([
            'type' => $this->type
        ]));
    }

    public function postCreate(TransaksiRequest $request, CreateTransaksiService $service)
    {
        $data = $service->save($this->model, $request);
        return Response::redirectBack($data);
    }

    public function getUpdate($code)
    {
        $data = Kotor::find($code);
        $detail = Transaksi::where(Transaksi::field_code_scan(), $code)->get();

        $jenis = Query::getJenisByCustomerCode($data->customer_code);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        return moduleView(modulePathForm('form', 'transaksi'), $this->share([
            'model' => $data,
            'jenis' => $jenis,
            'detail' => $detail,
            'type' => $this->type
        ]));
    }

    public function postUpdate($code, TransaksiRequest $request, UpdateTransaksiService $service)
    {
        $data = $service->update($this->model, $request, $code);

        return Response::redirectBack($data);
    }

    public function getQc($code)
    {
        $data = Kotor::find($code);
        $detail = Transaksi::where('transaksi_code_scan', $code)->get();

        $jenis = Query::getJenisByCustomerCode($data->customer_code);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        return moduleView(modulePathForm('qc', 'transaksi'), $this->share([
            'model' => $data,
            'jenis' => $jenis,
            'detail' => $detail,
            'type' => $this->type
        ]));
    }

    public function getPrintKotor($code)
    {
        $code = cleanCode($code);
        $model = Kotor::with('has_customer')->find($code);

        $detail = Transaksi::select([
            'jenis_nama',
            'transaksi_code_customer',
            'transaksi_report',
            'transaksi_scan',
            'jenis_nama',
        ])
        ->where('transaksi_code_scan', $code)
        ->where('transaksi_scan', '>', 0)
        ->leftJoinRelationship('has_jenis')->get();

        $jenis = Query::getJenisByCustomerCode($model->customer_code ?? null);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        return moduleView(modulePathForm('print_kotor', 'transaksi'), $this->share([
            'model' => $model,
            'customer' => $model->has_customer,
            'jenis' => $jenis,
            'data' => $detail,
            'print' => true,
            'type' => $this->type
        ]));
    }

    public function getPacking($code)
    {
        $data = Kotor::find($code);
        $detail = Transaksi::where('transaksi_code_scan', $code)->get();

        $jenis = Query::getJenisByCustomerCode($data->customer_code);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        return moduleView(modulePathForm('packing', 'transaksi'), $this->share([
            'model' => $data,
            'jenis' => $jenis,
            'detail' => $detail,
            'type' => $this->type
        ]));
    }

    public function getPrintPacking($code)
    {
        $model = Transaksi::with(['has_customer', 'has_jenis'])->find($code);
        $customer = $model->has_customer ?? false;
        $jenis = $model->has_jenis ?? false;

        return moduleView(modulePathForm('print_packing', 'transaksi'), $this->share([
            'jenis' => $jenis,
            'customer' => $customer,
            'model' => $model,
            'print' => true,
            'type' => $this->type
        ]));
    }

    public function getPrintBersih($code)
    {
        $data = Transaksi::select([
            'jenis_nama',
            'transaksi_code_customer',
            'transaksi_status',
            'transaksi_report',
            'transaksi_bersih',
            'jenis_nama',
        ])
            ->leftJoinRelationship('has_jenis')
            ->where('transaksi_code_scan', $code)
             ->where('transaksi_bersih', '>', 0)
            ->get();

        $model = $data->first();

        $customer = Customer::find($model->transaksi_code_customer) ?? false;

        $unic = '';

        if($model->transaksi_status == TransactionType::KOTOR)
        {
            $unic = env('CODE_KOTOR', 'KTR');
        }
        else if($model->transaksi_status == TransactionType::REJECT)
        {
            $unic = env('CODE_REJECT', 'RJK');
        }
        else if($model->transaksi_status == TransactionType::REWASH)
        {
            $unic = env('CODE_REWASH', 'RWS');
        }

        $unic = env('CODE_BERSIH', 'BSH').'-'.$unic.'-'.$model->transaksi_code_customer.'-'.date('Ymd').unic(5);

        $startDate = Carbon::createFromFormat('Y-m-d H:i', date('Y-m-d').' 13:00');
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d').' 23:59:59');

        $check_date = Carbon::now()->between($startDate, $endDate);
        $report_date = Carbon::now();

        if ($check_date) {
            $report_date = Carbon::now()->addDay(1);
        }

        $update =  Transaksi::query()
            ->where('transaksi_code_scan', $code)
            ->whereNull('transaksi_code_bersih')

            ->update([
                'transaksi_code_bersih' => $unic,
                'transaksi_report' => $report_date->format('Y-m-d')
            ]);

        $model = Transaksi::where('transaksi_code_scan', $code)->first();

        return moduleView(modulePathForm('print_bersih', 'transaksi'), $this->share([
            'customer' => $customer,
            'data' => $data,
            'model' => $model,
            'print' => true,
        ]));
    }

    public function getDelete()
    {
        $code = request()->get('code');
        $check = Transaksi::where('transaksi_code_scan', $code)->delete();
        $data = ["Gagal"];
        if($check)
        {
            $data = Notes::delete($data);
            Alert::delete("success");
        }

        return Response::redirectBack($check);
    }

    public function deleteData($code)
    {
        $code = array_unique(request()->get('code'));

        $check = Transaksi::whereIn('transaksi_code_scan', $code)->delete();
        $data = ["Gagal"];

        if($check)
        {
            $data = Notes::delete($data);
            Alert::delete("Data berhasil di hapus !");
        }
        else{
            Alert::error('Error !');
        }

        return $data;
    }

}
