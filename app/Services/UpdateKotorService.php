<?php

namespace App\Services;

use App\Dao\Models\DetailKotor;
use Plugins\Alert;
use Plugins\Notes;

class UpdateKotorService
{
    public function update($repository, $data, $code)
    {
       try {

        $bersih = collect($data->data)->sum('kotor_bersih') > 0 ? true : false;
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
            $update = ['kotor_scan' => $quantity['kotor_scan']];
            if($quantity['kotor_qc'] >= 0)
            {
                $update['kotor_qc'] = $quantity['kotor_qc'];
                $update['kotor_qc_at'] = $now;
                $update['kotor_qc_by'] = $user;
            }

            if($quantity['kotor_bersih'] >= 0)
            {
                $update['kotor_bersih'] = $quantity['kotor_bersih'];
                $update['kotor_pending'] = $quantity['kotor_bersih'] - $quantity['kotor_qc'];
                $update['kotor_code_packing'] = $code_packing.$key;
                $update['kotor_bersih_at'] = $now;
                $update['kotor_bersih_by'] = $user;
            }

            $kotorDetail = DetailKotor::where('kotor_code_scan', $code)
                ->where('kotor_id_jenis', $key)->update($update);
        }

        Alert::update();

        return Notes::create($insert);

       } catch (\Throwable $th) {

            Alert::error($th->getMessage());

            return $th->getMessage();
       }
    }
}
