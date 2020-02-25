<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Providers;

use Illuminate\Support\ServiceProvider;

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

    }

    public function provides()
    {
    }
}