<?php

namespace App\Services;

use App\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService {

    /**
     * All settings are loaded once and cached for 24 hours.
     * A single DB query per cache miss instead of one per Settings::get() call.
     */
    protected function all_cached(): array
    {
        return Cache::remember('app_settings_all', 1440, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    public function get($key, $default = null)
    {
        $all = $this->all_cached();
        return $all[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->all_cached();
    }

    /**
     * Call this after saving a setting to bust the cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('app_settings_all');
    }
}
