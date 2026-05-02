<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            // System Info
            'system_name'      => 'required|string|max:255',
            'system_status'    => 'nullable|in:open,maintenance',
            'system_logo'      => 'nullable|image|mimes:png,jpg,jpeg,svg|max:4048',
            'system_icon'      => 'nullable|image|mimes:png,jpg,jpeg,ico|max:2048',

            // Invoice Settings
            'invoice_name'     => 'required|string|max:255',
            'invoice_address'  => 'nullable|string|max:500',
            'invoice_phone'    => 'nullable|string|max:50',
            'invoice_email'    => 'nullable|email|max:255',
            'invoice_footer'   => 'nullable|string|max:5000',

            // Eye Test Settings
            'eye_test_name'     => 'required|string|max:255',
            'eye_test_address' => 'nullable|string|max:500',
            'eye_test_phone'   => 'nullable|string|max:50',
            'eye_test_email'   => 'nullable|email|max:255',
            'eye_test_footer'  => 'nullable|string|max:5000',
        ];
    }
}
