<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NotiFireWhatsAppService;
use App\Services\InvoiceWhatsAppNotifier;

class NotiFireServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register WhatsApp Service as Singleton
        $this->app->singleton(NotiFireWhatsAppService::class, function ($app) {
        return new NotiFireWhatsAppService();
        });

        // Register Invoice WhatsApp Notifier as Singleton
        $this->app->singleton(InvoiceWhatsAppNotifier::class, function ($app) {
            return new InvoiceWhatsAppNotifier(
                $app->make(NotiFireWhatsAppService::class)
            );
        });

        // Register facade alias
        $this->app->alias(NotiFireWhatsAppService::class, 'whatsapp');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/notifire.php' => config_path('notifire.php'),
            ], 'notifire-config');
        }
    }
}
