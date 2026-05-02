<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name} {--interface} {--type=}';


    protected $description = 'Command description';


    public function handle()
    {
        $serviceName = $this->argument('name') . "Service";
        $createInterface = $this->option('interface');
        $createType = $this->option('type');
        $interfaceName = $serviceName.'Interface';

        $serviceNamePath = app_path('Services/'.$serviceName.'.php');
        $interfaceNamePath = app_path('Interfaces/'.$interfaceName.'.php');

        if(File::exists($serviceNamePath)){
            $this->error('Service already exists');
        }


        $serviceStubs = "<?php
namespace App\Services;\n\n"
            .($createInterface ?"use App\\Interfaces\\".$interfaceName.";\n\n" :"")."class {$serviceName} ".($createInterface ? "implements {$interfaceName} \n" : "")
            ."{

}";

        $interfaceStubs = "<?php
namespace App\Interfaces;

interface {$interfaceName} {

}
";


        $serviceDirectory = app_path('Services');
        $interfaceDirectory = app_path('Interfaces');

        if(!File::isDirectory($serviceDirectory)){
            File::makeDirectory($serviceDirectory);
        }

        File::put($serviceNamePath, $serviceStubs);

        if($createInterface){
            if(!File::isDirectory($interfaceDirectory)){
                File::makeDirectory($interfaceDirectory);
            }

            File::put($interfaceNamePath, $interfaceStubs);

            $serviceProviderPath = app_path('Providers/AppServiceProvider.php');
            $type = $createType ? "singleton" : "bind";
            $serviceProviderStubs = "\n     \$this->app->{$type}(\App\\Interfaces\\{$interfaceName}::class,\App\\Services\\{$serviceName}::class);" ;
            $content = File::get($serviceProviderPath);
            $pattern = '/public function register\(\)\s*\{/';
            $replacement = "public function register()\n    {\n        {$serviceProviderStubs}";
            if(!str_contains($content,$serviceProviderStubs)){

                $content = preg_replace($pattern, $replacement, $content);

            }

            File::put($serviceProviderPath,$content);
        }

        $this->info('Service has been created');


    }
}
