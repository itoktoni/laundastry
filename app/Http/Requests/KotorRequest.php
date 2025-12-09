<?php

namespace App\Http\Requests;

use App\Dao\Traits\ValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class KotorRequest extends FormRequest
{
    use ValidationTrait;

    public function validation(): array
    {
        return [
            'customer_code' => 'required',
        ];
    }

    public function prepareForValidation()
    {
        $customer_code = $this->customer_code;
        $code = 'KTR'.$customer_code.unic(5);

        if (request()->segment(5) == 'update')
        {
            $code = request()->segment(6);
        }

        $date = $this->tanggal ?? date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $user = auth()->user()->id;

        $data = [];
        foreach (request('qty', []) as $key => $value) {

            $data[$key] = [
                'kotor_id' => $value['kotor_id'] ?? null,
                'kotor_code_customer' => $customer_code,
                'kotor_id_jenis' => $key,
                'kotor_code_category' => $this->kotor_status ?? 'NORMAL',
                'kotor_code_scan' => $code,
                'kotor_scan' => $value['scan'] ?? 0,
                'kotor_qc' => $value['qc'] ?? 0,
                'kotor_bersih' => $value['bersih'] ?? 0,
                'kotor_tanggal' => $date,
                'kotor_created_at' => $now,
                'kotor_updated_at' => $now,
                'kotor_created_by' => $user,
                'kotor_updated_by' => $user,
            ];
        }

        $this->merge([
            'data' => $data,
            'kotor_code' => $code,
        ]);
    }
}
