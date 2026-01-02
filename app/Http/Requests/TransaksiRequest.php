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

    public function withValidator($validator)
    {
        $data = collect(request('qty'));

        $validator->after(function ($validator) use ($data) {

            $max = env('MAXIMUM_QC', 20);

            foreach($data as $qc){
                $kotor = intval($qc['scan'] ?? 0);
                $qc = intval($qc['qc'] ?? 0);

                if($message = $this->checkQC($kotor, $qc))
                {
                    $validator->errors()->add('customer_code', $message);
                }
            }

        });
    }

    private function checkQC($kotor, $qc)
    {
        $kotor = intval($kotor);
        $qc = intval($qc);
        $max = env('MAXIMUM_QC', 0);

        if($qc == 0 || $max == 0)
        {
            return false;
        }

        $plus = $kotor + $max;

        if($qc > $kotor && $plus < $qc)
        {
            return "QC {$qc} lebih dari toleransi {$plus} ";
        }

        $minus = $kotor - $max;
        if($kotor > $qc && $minus > $qc)
        {
            return "QC {$qc} kurang dari toleransi {$minus} ";
        }
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
                // 'transaksi_id' => $value['kotor_id'] ?? null,
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
