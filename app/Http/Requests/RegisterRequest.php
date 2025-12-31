<?php

namespace App\Http\Requests;

use App\Dao\Traits\ValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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

            if($data->sum('qty') == 0)
            {
                $validator->errors()->add('customer_code', 'Harus ada minimal 1 Linen yang dikirim !');
            }
        });
    }


    public function prepareForValidation()
    {
        $customer_code = $this->customer_code;
        $code = 'REG'.$customer_code.unic(5);

        if (request()->segment(5) == 'update')
        {
            $code = request()->get('register_code');
        }

        $date = $this->tanggal ?? date('Y-m-d');
        $now = date('Y-m-d H:i:s');
        $user = auth()->user()->id;

        $data = [];
        foreach (request('qty', []) as $key => $value) {

            $data[$key] = [
                // 'register_id' => $value['register_id'] ?? null,
                'register_code_customer' => $customer_code,
                'register_code' => $code,
                'register_id_jenis' => $key,
                'register_qty' => $value['qty'] ?? 0,
                'register_tanggal' => $date,
                'register_created_at' => $now,
                'register_updated_at' => $now,
                'register_created_by' => $user,
                'register_updated_by' => $user,
            ];
        }

        $this->merge([
            'data' => $data,
            'register_code' => $code,
        ]);
    }
}
