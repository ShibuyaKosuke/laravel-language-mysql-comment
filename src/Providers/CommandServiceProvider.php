<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Providers;

use Illuminate\Support\ServiceProvider;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Console\LangPublishCommand;

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

        $this->commands([
            'command.trans.publish',
        ]);

    }

    public function provides()
    {
        return [
            'command.trans.publish',
        ];
    }
}