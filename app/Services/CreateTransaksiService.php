<?php

namespace App\Services;

use App\Dao\Models\Transaksi;
use Plugins\Alert;

class CreateTransaksiService
{
    public function save($model, $data)
    {
        $check = false;
        try {
            foreach($data->data as $trans)
            {
                $check = Transaksi::create($trans);
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
