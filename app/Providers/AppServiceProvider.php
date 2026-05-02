<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Schema;
use App\Facades\Settings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Custom Blade Directives for Permissions

        // @role('admin')
        /*Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // @hasrole('admin|manager')
        Blade::directive('hasrole', function ($roles) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyRole({$roles})): ?>";
        });

        Blade::directive('endhasrole', function () {
            return "<?php endif; ?>";
        });

        // @permission('view-users')
        Blade::directive('permission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->can({$permission})): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });

        // @haspermission('view-users|edit-users')
        Blade::directive('haspermission', function ($permissions) {
            return "<?php if(auth()->check() && auth()->user()->hasAnyPermission({$permissions})): ?>";
        });

        Blade::directive('endhaspermission', function () {
            return "<?php endif; ?>";
        });*/
       session_start();

        Schema::defaultStringLength(191);

        if (\Schema::hasTable('settings')) {
            config([
                'notifire.username'  => Settings::get('whatsapp_username'),
                'notifire.device_id' => Settings::get('whatsapp_device_id'),
                'notifire.api_url'   => Settings::get('whatsapp_url'),
            ]);
        }
    }
}
