<?php

namespace App\Http\Controllers;

use App\Dao\Models\Pending;
use App\Dao\Models\Transaksi;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use App\Http\Requests\RegisterRequest;
use App\Services\CreateRegisterService;
use App\Services\UpdateRegisterService;
use Plugins\Query;
use Plugins\Response;

class PendingController extends MasterController
{
    use CreateFunction, UpdateFunction;
    public $type;

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

        $view = [
            'jenis' => $jenis,
            'model' => $this->model,
            'customer' => $customer,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function getTable()
    {
        $data = $this->getData();

        return $this->views($this->template('table', 'pending'), $this->share([
            'data' => $data,
            'fields' => $this->model::getModel()->getShowField(),
        ]));
    }

    public function getCreate()
    {
        $detail = Pending::query()
            ->where('transaksi_code_customer', request()->get('customer'))
            ->where('transaksi_report', request()->get('tanggal'))
            ->get();

        return $this->views($this->template('form', 'pending'), $this->share([
            'detail' => $detail,
        ]));
    }

    public function postCreate(RegisterRequest $request, CreateRegisterService $service)
    {
        $data = $service->save($this->model, $request);

        return Response::redirectBack($data);
    }

    public function getUpdate($code)
    {
        $model = $this->model::where('transaksi_id', $code)->first();
        $detail = $this->model::where('transaksi_id', $code)->get();

        $jenis = Query::getJenisPending($model->transaksi_code_customer, $model->transaksi_report);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisPending(request()->get('customer'), request()->get('tanggal'));
        }

        return $this->views($this->template('form', 'pending'), $this->share([
            'model' => $model,
            'jenis' => $jenis,
            'detail' => $detail,
        ]));
    }

    public function postUpdate($code, RegisterRequest $request, UpdateRegisterService $service)
    {
        $data = $service->update($this->model, $request, $code);

        return Response::redirectBack($data);
    }

    public function deleteData($code)
    {
        $code = array_unique(request()->get('code'));
        $data = $this->model::whereIn('register_code', $code)->delete();

        return $data;
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

    public function getPrint($code)
    {
        $model = $this->model::with(['has_customer', 'has_jenis'])->where('transaksi_id', $code)->first();
        $customer = $model->has_customer ?? false;
        $jenis = $model->has_jenis ?? false;

        return $this->views($this->template('form', 'table'), $this->share([
            'jenis' => $jenis,
            'customer' => $customer,
            'model' => $model,
            'print' => true,
        ]));
    }

}
