<?php

namespace App\Http\Controllers;

use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Register;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use App\Http\Requests\RegisterRequest;
use App\Services\CreateRegisterService;
use App\Services\UpdateRegisterService;
use Illuminate\Support\Facades\DB;
use Plugins\Alert;
use Plugins\Query;
use Plugins\Response;

class RegisterController extends MasterController
{
    use CreateFunction, UpdateFunction;

    public function __construct(Register $model, SingleService $service)
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

        $category = ['REGISTER' => 'REGISTER'];

        $view = [
            'category' => $category,
            'jenis' => $jenis,
            'model' => false,
            'customer' => $customer,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function getData()
    {
        $query = $this->model
            ->addSelect(['register_code', 'register_tanggal', 'customer_nama', 'jenis_nama', DB::raw('SUM(register_qty) as total_qty')])
            ->leftJoinRelationship('has_customer')
            ->leftJoinRelationship('has_jenis')
            ->groupBy('register_code')
            ->orderBy('register_tanggal', 'DESC')
            ->filter();

        $per_page = env('PAGINATION_NUMBER', 10);
        if(request()->get('per_page'))
        {
            $per_page = request()->get('per_page');
        }

        $query = env('PAGINATION_SIMPLE') ? $query->simplePaginate($per_page) : $query->fastPaginate($per_page);

        return $query;
    }

    public function getUpdate($code)
    {
        $model = $this->model::where('register_code', $code)->first();
        $detail = $this->model::where('register_code', $code)->get();

        $jenis = Query::getJenisByCustomerCode($model->register_code_customer);

        if(request()->has('customer'))
        {
            $jenis = Query::getJenisByCustomerCode(request()->get('customer'));
        }

        return $this->views($this->template(), $this->share([
            'model' => $model,
            'jenis' => $jenis,
            'detail' => $detail,
        ]));
    }

    public function getCreate()
    {
        return $this->views($this->template(core : self::$is_core), $this->share());
    }

    public function postCreate(RegisterRequest $request, CreateRegisterService $service)
    {
        $data = $service->save($this->model, $request);

        return Response::redirectBack($data);
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
        $model = $this->model::with(['has_customer', 'has_jenis'])->where('register_code', $code)->first();
        $customer = $model->has_customer ?? false;
        $jenis = $model->has_jenis ?? false;
        $data = $this->model->addSelect([Register::getTableName().'.*', Jenis::field_name()])
            ->leftJoinRelationship('has_jenis')
            ->where('register_code', $code)
            ->orderBy(Jenis::field_name(), 'ASC')
            ->get();

       return $this->views($this->template(), $this->share([
            'jenis' => $jenis,
            'customer' => $customer,
            'data' => $data,
            'model' => $model,
            'print' => true,
        ]));
    }

}
