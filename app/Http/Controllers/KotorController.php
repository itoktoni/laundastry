<?php

namespace App\Http\Controllers;

use App\Dao\Models\Category;
use App\Dao\Models\Customer;
use App\Dao\Models\Jenis;
use App\Dao\Models\Kotor;
use App\Http\Controllers\Core\MasterController;
use App\Http\Function\CreateFunction;
use App\Http\Function\UpdateFunction;
use App\Services\Master\SingleService;

class KotorController extends MasterController
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
        $jenis = Jenis::getOptions();
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
}
