<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Providers;

use Illuminate\Support\ServiceProvider;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Console\LangPublishCommand;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Console\MakeControllerCommand;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Console\MakeModelCommand;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Console\MakeRequestCommand;

/**
 * Class CommandServiceProvider
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Providers
 */
class CommandServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->registerCommands();
    }

    public function register()
    {
    }

    protected function registerCommands()
    {
        $this->app->singleton('command.trans.publish', function () {
            return new LangPublishCommand();
        });

        $this->app->singleton('command.make.controller', function ($app) {
            return new MakeControllerCommand($app['files']);
        });

        $this->app->singleton('command.make.model', function ($app) {
            return new MakeModelCommand($app['files']);
        });

        $this->app->singleton('command.make.request', function ($app) {
            return new MakeRequestCommand($app['files']);
        });

        $this->commands([
            'command.trans.publish',
            'command.make.controller',
            'command.make.model',
            'command.make.request'
        ]);
    }

    public function provides()
    {
        return [
            'command.trans.publish',
            'command.make.controller',
            'command.make.model',
            'command.make.request'
        ];
    }
}
