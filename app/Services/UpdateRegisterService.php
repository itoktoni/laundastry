<?php

namespace App\Services;

use App\Dao\Models\DetailKotor;
use App\Dao\Models\Register;
use Plugins\Alert;
use Plugins\Notes;

class UpdateRegisterService
{
    public function update($repository, $data, $code)
    {
       try {
        foreach ($data->data as $key => $quantity) {

            Register::updateOrCreate([
                'register_id_jenis' => intval($quantity['register_id_jenis']),
                'register_code' => $quantity['register_code']
            ], $quantity);

            // Register::where('register_id_jenis', intval($quantity['register_id_jenis']))
            //     ->where('register_code', $quantity['register_code'])
            //     ->update(['register_qty' => $quantity['register_qty'] ]);
        }

        Alert::update();

        return Notes::create("Update successful");

       } catch (\Throwable $th) {

            Alert::error($th->getMessage());

            return $th->getMessage();
       }
    }
}
