<?php

namespace App\Facades;

use App\Services\SettingsService;
use Illuminate\Support\Facades\Facade;

class Settings extends Facade {

   public static function getFacadeAccessor(){

    return SettingsService::class; 

    } 

}
