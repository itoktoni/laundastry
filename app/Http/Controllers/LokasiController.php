<?php

namespace App\Http\Controllers;

use App\Dao\Enums\JenisType;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use App\Facades\Model\LokasiModel;
use Plugins\Query;

class LokasiController extends MasterController
{
    use CreateFunction, UpdateFunction;

    public function __construct(LokasiModel $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    protected function share($data = [])
    {
        $customer = Query::getCustomerByUser();

        $view = [
            'model' => false,
            'customer' => $customer,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function getData()
    {
        $query = $this->model->select([$this->model->getTable().'.*', 'customer_nama'])
            ->leftJoinRelationship('has_customer')
            ->filter();

        $query = $query->paginate(100);

        return $query;
    }
}
