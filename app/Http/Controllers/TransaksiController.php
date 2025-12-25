<?php

namespace App\Http\Controllers;

use App\Dao\Models\Category;
use App\Dao\Models\Customer;
use App\Dao\Models\DetailKotor;
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

class TransaksiController extends MasterController
{
    use CreateFunction, UpdateFunction;

    public $type = null;

    public function __construct(Transaksi $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    protected function beforeForm()
    {
        $customer = Query::getCustomerByUser();
        $jenis = [];

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        $category = Category::getOptions();

        self::$share = [
            'category' => $category,
            'jenis' => $jenis,
            'customer' => $customer,
        ];
    }

    public function getTable()
    {
        $this->beforeForm();

        $data = $this->getData();
        return moduleView(modulePathTable(core: self::$is_core), $this->share([
            'data' => $data,
            'type' => $this->type,
            'fields' => $this->model::getModel()->getShowField(),
        ]));
    }

    public function getCreate()
    {
        $this->beforeForm();
        $this->beforeCreate();

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
        $this->beforeForm();
        $this->beforeUpdate($code);

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
        $this->beforeForm();
        $this->beforeUpdate($code);

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
        $model = Kotor::with('has_customer')->find($code);

        $detail = Transaksi::select([
            'jenis_nama',
            'transaksi_code_customer',
            'transaksi_report',
            'transaksi_scan',
            'jenis_nama',
        ])
        ->where('transaksi_scan', '>', 0)
        ->leftJoinRelationship('has_jenis')->get();

        $jenis = Query::getJenisByCustomerCode($model->customer_code);

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

        $bersih = 'DLV'.$customer->customer_code.date('Ymd').unic(5);

        $update =  Transaksi::query()
            ->where('transaksi_code_scan', $code)
            ->whereNull('transaksi_code_bersih')

            ->update([
                'transaksi_code_bersih' => $bersih,
                'transaksi_report' => date('Y-m-d')
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
        $check = DetailKotor::where('kotor_code_scan', $code)->delete();
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

        $check = DetailKotor::where('kotor_code_scan', $code)->delete();
        $data = ["Gagal"];

        if($check)
        {
            $data = Notes::delete($data);
            Alert::delete("success");
        }


        return $data;
    }

}
