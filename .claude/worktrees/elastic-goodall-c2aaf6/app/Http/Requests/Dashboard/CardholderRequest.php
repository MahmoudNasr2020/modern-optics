<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CardholderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'cardholder_name' => ['required','string','max:256','unique:cardholders,cardholder_name,'.$this->route('id')],
            'status' => ['required','in:0,1'],

            'categories' => ['nullable','array'],
            'categories.*' => ['nullable', 'numeric'],
        ];
    }
}
