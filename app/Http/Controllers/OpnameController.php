<?php

namespace App\Http\Controllers;

use App\Dao\Enums\OpnameType;
use App\Dao\Models\Customer;
use App\Dao\Models\Opname;
use App\Dao\Models\OpnameDetail;
use App\Dao\Models\Register;
use App\Http\Controllers\Core\MasterController;
use App\Http\Requests\Core\DeleteRequest;
use App\Http\Requests\OpnameRequest;
use App\Services\Master\CreateService;
use App\Services\Master\DeleteService;
use App\Services\Master\SingleService;
use App\Services\Master\UpdateService;
use Plugins\Alert;
use Plugins\Query;
use Plugins\Response;

class OpnameController extends MasterController
{
    public function __construct(Opname $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    protected function share($data = [])
    {
        $customer = Query::getCustomerByUser();
        $status = OpnameType::getOptions();

        $view = [
            'model' => $this->model,
            'customer' => $customer,
            'status' => $status,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function postCreate(OpnameRequest $request, CreateService $service)
    {
        $data = $service->save($this->model, $request);

        return Response::redirectBack($data);
    }

    public function postUpdate($code, OpnameRequest $request, UpdateService $service)
    {
        $data = $service->update($this->model, $request, $code);

        return Response::redirectBack($data);
    }

    public function getData()
    {
        $query = Opname::query()
            ->addSelect([$this->model->getTable().'.*', Customer::field_name()])
            ->leftJoinRelationship('has_customer')
            ->filter();

        $per_page = env('PAGINATION_NUMBER', 10);
        if(request()->get('per_page'))
        {
            $per_page = request()->get('per_page');
        }

        $query = env('PAGINATION_SIMPLE') ? $query->simplePaginate($per_page) : $query->fastPaginate($per_page);

        return $query;
    }

    public function postDelete(DeleteRequest $request, DeleteService $service)
    {
        $code = $request->get('code');
        $data = $service->delete($this->model, $code);

        OpnameDetail::whereIn('odetail_id_opname', $code)->delete();

        return Response::redirectBack($data);
    }

    public function getDelete()
    {
        $code = request()->get('code');
        $data = self::$service->delete($this->model, $code);

        OpnameDetail::where('odetail_id_opname', $code)->delete();

        return Response::redirectBack($data);
    }

    public function deleteData($code)
    {
        $code = array_unique(request()->get('code'));
        $data = self::$service->delete($this->model, $code);

        OpnameDetail::whereIn('odetail_id_opname', $code)->delete();

        return $data;
    }

    public function getCapture($code)
    {
        try {

            $opname = Opname::find($code);
            $customer = $opname->opname_code_customer;

            $register = Register::where('register_code_customer', $customer)
                ->get()->groupBy('register_id_jenis')->map(function ($row) {
                    return $row->sum('register_qty');
            });

            $data = [];

            foreach($register as $jenis => $qty)
            {
                $data[] = [
                    'odetail_id_opname' => $code,
                    'odetail_id_jenis' => $jenis,
                    'odetail_code_customer' => $customer,
                    'odetail_register' => $qty,
                ];
            }

            if(!empty($data))
            {
                OpnameDetail::insert($data);
            }

            $opname->update([
                'opname_capture' => date('Y-m-d H:i:s')
            ]);

            Alert::create("Opname berhasil dicapture !");

        } catch (\Throwable $th) {

            Alert::error($th->getMessage());
        }

        return redirect()->back();
    }

    public function getOpname($code)
    {
        $model = Opname::find($code);
        $detail = OpnameDetail::where('odetail_id_opname', $model->field_primary)->get();
        $jenis = Query::getJenisByCustomerCode($model->opname_code_customer);

        return $this->views($this->template(), $this->share([
            'model' => $model,
            'jenis' => $jenis,
            'detail' => $detail,
        ]));
    }

    public function getPrint($code)
    {
        $model = OpnameDetail::with(['has_customer', 'has_jenis'])->where('odetail_id', $code)->first();
        $customer = $model->has_customer ?? false;
        $jenis = $model->has_jenis ?? false;

        return $this->views($this->template(), $this->share([
            'jenis' => $jenis,
            'customer' => $customer,
            'model' => $model,
            'print' => true,
        ]));
    }
}
