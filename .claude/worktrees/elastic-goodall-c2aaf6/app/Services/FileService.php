<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService {

    protected $disk;

    public function __construct($disk='public') {
        $this->disk = $disk;
    }

    public function upload($file,$folder = 'uploads',$disk=null){
        $disk = $disk ?? $this->disk;
        $fileName = Str::uuid().'.'.$file->extension();
        return $file->storeAs($folder, $fileName,$disk);
    }


    public function uploadMultiple($files,$folder = 'uploads', $disk=null){
        $disk = $disk ?? $this->disk;
        return collect($files)->map(function($file) use ($folder,$disk){
            return $this->upload($file,$folder,$disk);
        })->toArray();
    }

    public function delete($path,$disk=null){
        $disk = $disk ?? $this->disk;
        if(Storage::disk($disk)->exists($path))
        {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }

    public function deleteMultiple($paths,$disk=null){
        $disk = $disk ?? $this->disk;
        return collect($paths)->map(function($path) use ($disk){
            return $this->delete($path,$disk);
        })->toArray();
    }

    public function getUrl($path,$disk = null,$fallback = null){
        $disk = $disk ?? $this->disk;
        if ($path && Storage::disk($disk)->exists($path)){
            return Storage::disk($disk)->url($path);
        }
        return $fallback ?? asset('images/default.jpg');
    }

    public function download($path,$disk = null){
        $disk = $disk ?? $this->disk;
        if($path && Storage::disk($disk)->exists($path)){
            return Storage::disk($disk)->download($path);
        }
        return false;
    }

    public function getPrivateFileUrl($path, $disk = null, $folder = null ,$fallback = null){
        $disk = $disk ?? $this->disk;
        $folder = $folder ?? $disk;
        if($path && Storage::disk($disk)->exists($path)){
            return response()->file(storage_path('app/'.$folder.'/'.$path));
        }
        return $fallback ?? asset('images/default.jpg');
    }

}


