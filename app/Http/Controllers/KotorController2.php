<?php

namespace App\Http\Controllers;

use App\Dao\Models\Category;
use App\Dao\Models\Customer;
use App\Dao\Models\DetailKotor;
use App\Dao\Models\Kotor;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Http\Requests\Core\DeleteRequest;
use App\Http\Requests\KotorRequest;
use App\Services\CreateKotorService;
use App\Services\Master\DeleteService;
use App\Services\Master\SingleService;
use App\Services\UpdateKotorService;
use Plugins\Alert;
use Plugins\Notes;
use Plugins\Query;
use Plugins\Response;

class KotorController2 extends MasterController
{
    use CreateFunction, UpdateFunction;

    public function __construct(Kotor $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    protected function beforeForm()
    {
        $customer = Customer::getOptions();
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

    public function getData()
    {
        $query = $this->model->dataRepository();
        return $query;
    }

    public function postCreate(KotorRequest $request, CreateKotorService $service)
    {
        $data = $service->save($this->model, $request);

        return Response::redirectBack($data);
    }

    public function postUpdate($code, KotorRequest $request, UpdateKotorService $service)
    {
        $data = $service->update($this->model, $request, $code);

        return Response::redirectBack($data);
    }

    public function getUpdate($code)
    {
        $this->beforeForm();
        $this->beforeUpdate($code);

        $data = $this->get($code);
        $detail = DetailKotor::where('kotor_code_scan', $code)->get();

        $jenis = Query::getJenisByCustomerCode($data->customer_code);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        return moduleView(modulePathForm(), $this->share([
            'model' => $data,
            'jenis' => $jenis,
            'detail' => $detail,
        ]));
    }

    public function getPrint($code)
    {
        $model = $this->get($code, ['has_customer']);

        $detail = DetailKotor::select([
            'jenis_nama',
            'kotor_code_customer',
            'kotor_report',
            'kotor_scan',
            'jenis_nama',
        ])
        ->where('kotor_scan', '>', 0)
        ->leftJoinRelationship('has_jenis')->get();

        $jenis = Query::getJenisByCustomerCode($model->customer_code);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        return moduleView(modulePathPrint(), $this->share([
            'model' => $model,
            'customer' => $model->has_customer,
            'jenis' => $jenis,
            'data' => $detail,
            'print' => true,
        ]));
    }

    public function getQc($code)
    {
        $this->beforeForm();
        $this->beforeUpdate($code);

        $data = $this->get($code);
        $detail = DetailKotor::where('kotor_code_scan', $code)->get();

        $jenis = Query::getJenisByCustomerCode($data->customer_code);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        return moduleView(modulePathForm('qc'), $this->share([
            'model' => $data,
            'jenis' => $jenis,
            'detail' => $detail,
        ]));
    }

    public function getPacking($code)
    {
        $this->beforeForm();
        $this->beforeUpdate($code);

        $data = $this->get($code);
        $detail = DetailKotor::where('kotor_code_scan', $code)->get();

        $jenis = Query::getJenisByCustomerCode($data->customer_code);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        return moduleView(modulePathForm('packing'), $this->share([
            'model' => $data,
            'jenis' => $jenis,
            'detail' => $detail,
        ]));
    }

    public function getPrintPacking($code)
    {
        $model = DetailKotor::with(['has_customer', 'has_jenis'])->find($code);
        $customer = $model->has_customer ?? false;
        $jenis = $model->has_jenis ?? false;
        return moduleView(modulePathPrint('print_packing'), $this->share([
            'jenis' => $jenis,
            'customer' => $customer,
            'model' => $model,
            'print' => true,
        ]));
    }

    public function getPrintBersih($code)
    {
        $data = DetailKotor::select([
            'jenis_nama',
            'kotor_code_customer',
            'kotor_report',
            'kotor_bersih',
            'jenis_nama',
        ])
            ->leftJoinRelationship('has_jenis')
            ->where('kotor_code_scan', $code)
            ->where('kotor_bersih', '>', 0)
            ->get();

        $model = $data->first();

        $customer = Customer::find($model->kotor_code_customer) ?? false;

        $bersih = 'DLV'.$customer->customer_code.date('Ymd').unic(5);

        $update =  DetailKotor::query()
            ->where('kotor_code_scan', $code)
            ->whereNull('kotor_code_bersih')

            ->update([
                'kotor_code_bersih' => $bersih,
                'kotor_report' => date('Y-m-d')
            ]);

        $model = DetailKotor::where('kotor_code_scan', $code)->first();

        return moduleView(modulePathPrint('print_bersih'), $this->share([
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
