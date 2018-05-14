<?php namespace Yorki\Workshop;

use Yorki\Workshop\Console\Commands\WorkshopAdmin;
use Yorki\Workshop\Console\Commands\WorkshopModel;
use Yorki\Workshop\Console\Commands\WorkshopStaticpages;

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

        $this->app->singleton('command.workshop.static-pages', function ($app) {
            return new WorkshopStaticpages($app['files']);
        });

        $this->commands('command.workshop.admin');
        $this->commands('command.workshop.model');
        $this->commands('command.workshop.static-pages');
    }
}