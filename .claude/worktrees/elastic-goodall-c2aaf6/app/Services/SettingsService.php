<?php

namespace App\Services;

use App\Setting;

class SettingsService {

    public function get($key,$default=null) {
        return Setting::where('key', $key)->first()->value ?? $default;
    }
    public function all(){
        return Setting::pluck('value', 'key')->toArray();
    }
}
