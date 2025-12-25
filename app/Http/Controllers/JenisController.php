<?php

namespace App\Http\Controllers;

use App\Dao\Models\Customer;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;
use App\Facades\Model\JenisModel;

class JenisController extends MasterController
{
    use CreateFunction, UpdateFunction;

    public function __construct(JenisModel $model, SingleService $service)
    {
        self::$service = self::$service ?? $service;
        $this->model = $model::getModel();
    }

    protected function share($data = [])
    {
        $customer = Customer::getOptions();

        $view = [
            'model' => false,
            'customer' => $customer,
        ];

        return self::$share = array_merge($view, self::$share, $data);
    }

    public function getData()
    {
        $query = $this->model->dataRepository([$this->model->getTable().'.*', 'customer_nama'], ['has_customer']);
        return $query;
    }
}
