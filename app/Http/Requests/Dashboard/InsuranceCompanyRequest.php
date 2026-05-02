<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class InsuranceCompanyRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
          'company_name' => ['required','string','max:256','unique:insurance_companies,company_name,'.$this->route('id')],
            'status' => ['required','in:0,1'],

            'categories' => ['nullable','array'],
            'categories.*' => ['nullable', 'numeric'],
        ];
    }
}
