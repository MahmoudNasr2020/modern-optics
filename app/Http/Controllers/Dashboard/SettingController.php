<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\File;
use App\glassLense;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SettingRequest;
use App\PriceAdjustmentLog;
use App\Product;
use App\Services\SettingsService;
use App\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-settings')->only(['index']);
        $this->middleware('permission.spatie:edit-settings')->only(['update', 'priceAdjustment']);
    }

    public function index(){
        $priceAdjLogs = PriceAdjustmentLog::with('performer')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();
        return view('dashboard.pages.settings.show-settings', compact('priceAdjLogs'));
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

            if ($request->hasFile('invoice_logo_en')) {
                $this->deleteFile('invoice_logo_en');
                Setting::updateOrCreate(
                    ['key' => 'invoice_logo_en'],
                    ['value' => File::upload($request->file('invoice_logo_en'), 'settings')]
                );
            }

            if ($request->hasFile('invoice_logo_ar')) {
                $this->deleteFile('invoice_logo_ar');
                Setting::updateOrCreate(
                    ['key' => 'invoice_logo_ar'],
                    ['value' => File::upload($request->file('invoice_logo_ar'), 'settings')]
                );
            }

            // Define all fields to update
            $fields = [
                'system_name',
                'invoice_name',
                'invoice_name_ar',
                'invoice_address',
                'invoice_address_ar',
                'invoice_phone',
                'invoice_email',
                'invoice_footer',
                'invoice_footer_ar',
                'invoice_template',
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

            Setting::updateOrCreate(
                ['key' => 'show_spinner'],
                ['value' => $request->has('show_spinner') ? '1' : '0']
            );

            Setting::updateOrCreate(
                ['key' => 'show_chatbot'],
                ['value' => $request->has('show_chatbot') ? '1' : '0']
            );

            // Clear settings cache so changes take effect immediately
            Cache::forget('app_settings_all');

            session()->flash('success', 'Settings updated successfully!');
            return redirect()->back();

        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong while updating settings: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Bulk price adjustment — increase or decrease all product + lens prices by a percentage.
     */
    public function priceAdjustment(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'adjustment_type'    => 'required|in:increase,decrease',
            'adjustment_percent' => 'required|numeric|min:0.01|max:100',
            'apply_to'           => 'required|array|min:1',
            'apply_to.*'         => 'in:products,lenses',
        ]);

        $type    = $request->adjustment_type;   // increase | decrease
        $percent = (float) $request->adjustment_percent;
        $applyTo = $request->apply_to;          // ['products'] | ['lenses'] | ['products','lenses']

        $factor = $type === 'increase'
            ? 1 + ($percent / 100)
            : 1 - ($percent / 100);

        DB::beginTransaction();
        try {
            $productsCount = 0;
            $lensesCount   = 0;

            if (in_array('products', $applyTo)) {
                $productsCount = Product::count();
                // Only adjust sell price (retail_price). Purchase/cost price (price) is never touched.
                Product::query()->update([
                    'retail_price' => DB::raw("ROUND(retail_price * {$factor}, 2)"),
                ]);
            }

            if (in_array('lenses', $applyTo)) {
                $lensesCount = glassLense::count();
                glassLense::query()->update([
                    'retail_price' => DB::raw("ROUND(retail_price * {$factor}, 2)"),
                ]);
            }

            // Save log entry
            PriceAdjustmentLog::create([
                'type'               => $type,
                'percent'            => $percent,
                'apply_to'           => $applyTo,
                'products_affected'  => $productsCount,
                'lenses_affected'    => $lensesCount,
                'performed_by'       => auth()->id(),
            ]);

            DB::commit();

            $direction = $type === 'increase' ? 'increased' : 'decreased';
            $targets   = implode(' & ', array_map('ucfirst', $applyTo));
            session()->flash('success', "✅ {$targets} prices {$direction} by {$percent}% successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Price adjustment failed: ' . $e->getMessage());
        }

        return redirect(route('dashboard.settings.index') . '#section-prices');
    }

    protected function deleteFile($key)
    {
        $setting = Setting::where('key', $key)->first();
        if ($setting && $setting->value) {
            File::delete($setting->value);
        }
    }
}
