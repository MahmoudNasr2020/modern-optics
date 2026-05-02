<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFacade extends Command
{

    protected $signature = 'make:facade {name} {--service=}';


    protected $description = 'make facade for service';


    public function handle()
    {
        $name = $this->argument('name');
        $service = $this->option('service');
        $facades_path = app_path('Facades/'.ucfirst($name).'.php');

        if($service == ''){
            $this->error('Please set service');
            return;
        }

        if(File::exists($facades_path)){
            $this->error('Facades already exists!');
        }


        $facade_stubs = "<?php

namespace App\Facades;

use App\\Services\\{$service};
use Illuminate\\Support\\Facades\Facade;

class {$name} extends Facade {

   public static function getFacadeAccessor(){

    return {$service}::class; \n
    } \n
}
";
        if(!File::isDirectory(app_path('Facades'))){
            File::makeDirectory(app_path('Facades'));
        }

        File::put($facades_path, $facade_stubs);

        $this->info('Facade added successfully!');

    }
}
