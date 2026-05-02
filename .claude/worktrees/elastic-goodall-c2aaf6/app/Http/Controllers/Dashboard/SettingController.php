<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SettingRequest;
use App\Setting;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-settings')->only(['index']);
        $this->middleware('permission.spatie:edit-settings')->only(['update']);
    }

    public function index(){
        return view('dashboard.pages.settings.show-settings');
    }

    public function update(SettingRequest $request)
    {
        try {
            // Handle file uploads
            if ($request->hasFile('system_logo')) {
                $this->deleteFile('system_logo');
                Setting::updateOrCreate(
                    ['key' => 'system_logo'],
                    ['value' => File::upload($request->file('system_logo'), 'settings')]
                );
            }

            if ($request->hasFile('system_icon')) {
                $this->deleteFile('system_icon');
                Setting::updateOrCreate(
                    ['key' => 'system_icon'],
                    ['value' => File::upload($request->file('system_icon'), 'settings')]
                );
            }

            // Define all fields to update
            $fields = [
                'system_name',
                'invoice_name',
                'invoice_address',
                'invoice_phone',
                'invoice_email',
                'invoice_footer',
                'eye_test_name',
                'eye_test_address',
                'eye_test_phone',
                'eye_test_email',
                'eye_test_footer',
                // WhatsApp settings
                'whatsapp_device_id',
                'whatsapp_username',
                'whatsapp_url',
            ];

            // Update each field
            foreach ($fields as $field) {
                Setting::updateOrCreate(
                    ['key' => $field],
                    ['value' => $request->input($field)]
                );
            }

            // Handle checkboxes separately
            Setting::updateOrCreate(
                ['key' => 'system_status'],
                ['value' => $request->input('system_status') === 'maintenance' ? 'maintenance' : 'open']
            );

            Setting::updateOrCreate(
                ['key' => 'send_whatsapp'],
                ['value' => $request->has('send_whatsapp') ? 1 : 0]
            );

            session()->flash('success', 'Settings updated successfully!');
            return redirect()->back();

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong while updating settings: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    protected function deleteFile($key)
    {
        $setting = Setting::where('key', $key)->first();
        if ($setting && $setting->value) {
            File::delete($setting->value);
        }
    }
}
