<?php

namespace App\Http\Requests;

use App\Dao\Enums\TransactionType;
use App\Dao\Traits\ValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
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

        if($this->type == TransactionType::KOTOR)
        {
            $code = env('CODE_KOTOR', 'KTR');
        }
        else if($this->type == TransactionType::REJECT)
        {
            $code = env('CODE_REJECT', 'RJK');
        }
        else if($this->type == TransactionType::REWASH)
        {
            $code = env('CODE_REWASH', 'RWS');
        }

        $code = $code.'-'.$customer_code.'-'.date('Ymd').'-'.unic(5);

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
                'transaksi_id' => $value['kotor_id'] ?? null,
                'transaksi_code_customer' => $customer_code,
                'transaksi_id_jenis' => $key,
                'transaksi_code_category' => $this->transaksi_code_category ?? 'NORMAL',
                'transaksi_code_scan' => $code,
                'transaksi_scan' => $value['scan'] ?? 0,
                'transaksi_qc' => $value['qc'] ?? 0,
                'transaksi_bersih' => $value['bersih'] ?? 0,
                'transaksi_tanggal' => $date,
                'transaksi_status' => $this->type,
                'transaksi_created_at' => $now,
                'transaksi_updated_at' => $now,
                'transaksi_created_by' => $user,
                'transaksi_updated_by' => $user,
            ];
        }

        $this->merge([
            'data' => $data,
            'code' => $code,
        ]);
    }
}
