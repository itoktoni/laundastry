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
            $check = Transaksi::insert($data->data);
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
