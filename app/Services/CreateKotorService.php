<?php

namespace App\Services;

use App\Dao\Models\DetailKotor;
use Plugins\Alert;

class CreateKotorService
{
    public function save($model, $data)
    {
        $check = false;
        try {
            // $check = $model->saveRepository($data->all());
            $check = DetailKotor::insert($data->data);
            if ($check) {

                Alert::create();

            } else {

                $message = env('APP_DEBUG') ? $check['data'] : $check['message'];
                Alert::error($message);
            }
        } catch (\Throwable $th) {
            Alert::error($th->getMessage());

            return $th->getMessage();
        }

        return $check;
    }
}
