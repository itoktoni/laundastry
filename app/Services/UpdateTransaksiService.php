<?php

namespace App\Services;

use App\Dao\Models\DetailKotor;
use App\Dao\Models\Transaksi;
use Plugins\Alert;
use Plugins\Notes;

class UpdateTransaksiService
{
    public function update($repository, $data, $code)
    {
       try {

        $bersih = collect($data->data)->sum('transaksi_bersih') > 0 ? true : false;
        $code_packing = null;
        $insert = [];
        $now = date('Y-m-d H:i:s');
        $user = auth()->user()->id;

        if($bersih)
        {
            $code_packing = $code.'_PACK_';
        }

        foreach ($data->data as $key => $quantity) {
            // Update the specific quantity for this linen type
            $update = ['transaksi_scan' => $quantity['transaksi_scan']];
            $update = ['transaksi_code_category' => $quantity['transaksi_code_category']];

            if($quantity['transaksi_qc'] >= 0)
            {
                $update['transaksi_qc'] = $quantity['transaksi_qc'];
                $update['transaksi_qc_at'] = $now;
                $update['transaksi_qc_by'] = $user;
            }

            if($quantity['transaksi_bersih'] >= 0)
            {
                $update['transaksi_bersih'] = $quantity['transaksi_bersih'];
                $update['transaksi_pending'] =  $quantity['transaksi_qc'] - $quantity['transaksi_bersih'];
                $update['transaksi_code_packing'] = $code_packing.$key;
                $update['transaksi_bersih_at'] = $now;
                $update['transaksi_bersih_by'] = $user;
            }

            $update = array_merge($quantity, $update);

            Transaksi::updateOrCreate([
                'transaksi_id_jenis' => intval($key),
                'transaksi_code_scan' => $code
            ], $update);

            // $kotorDetail = Transaksi::where('transaksi_code_scan', $code)
            //     ->where('transaksi_id_jenis', $key)->update($update);
        }

        Alert::update();

        return Notes::create($insert);

       } catch (\Throwable $th) {

            Alert::error($th->getMessage());

            return $th->getMessage();
       }
    }
}
