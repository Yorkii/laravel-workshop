<?php namespace Yorki\Workshop;

use Yorki\Workshop\Console\Commands\WorkshopAdmin;
use Yorki\Workshop\Console\Commands\WorkshopModel;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.workshop.admin', function ($app) {
            return new WorkshopAdmin($app['files']);
        });

        $this->app->singleton('command.workshop.model', function ($app) {
            return new WorkshopModel($app['files']);
        });
    }
}