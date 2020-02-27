<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Rule\Repository;

class AppServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->registerCommands();

        $this->app->singleton('rules', function ($app) {
            $items = [];
            foreach (File::allFiles(base_path('rules')) as $splFileInfo) {
                $contents = require $splFileInfo->getRealPath();
                $items[$splFileInfo->getFilenameWithoutExtension()] = $contents;
            }
            return new Repository($items);
        });
        $this->app->alias('rules', Repository::class);
        $this->app->instance('rules', $this->app->rules);

        dd($this->app);
    }

    public function register()
    {
    }

    protected function registerCommands()
    {
        //
    }

    public function provides()
    {
        return [

        ];
    }

}