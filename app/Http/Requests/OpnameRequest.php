<?php

namespace App\Http\Requests;

use App\Dao\Traits\ValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class OpnameRequest extends FormRequest
{
    use ValidationTrait;

    public function validation(): array
    {
        return [
            'opname_mulai' => 'required',
            'opname_selesai' => 'required',
            'opname_code_customer' => 'required',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([

        ]);
    }
}
