<?php

namespace App\Services;

use App\Dao\Models\Register;
use Plugins\Alert;

class CreateRegisterService
{
    public function save($model, $data)
    {
        $check = false;
        try {

            foreach($data->data as $reg)
            {
                $check = Register::create($reg);
            }

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
