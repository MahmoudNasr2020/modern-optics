<?php

namespace App\Facades;

use App\Services\FileService;
use Illuminate\Support\Facades\Facade;

class File extends Facade {

   public static function getFacadeAccessor(){

    return FileService::class; 

    } 

}
