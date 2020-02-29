<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Rule\Repository;

/**
 * Class AppServiceProvider
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        $this->registerPath('rules');
        $this->registerCommands();
    }

    public function register()
    {
        $file = 'Helpers/ValidationRule.php';
        if (file_exists($file)) {
            require_once($file);
        }
    }

    protected function registerPath($name)
    {
        if (!file_exists(base_path($name))) {
            mkdir(base_path($name));
        }

        $this->app->alias($name, Repository::class);
        $this->app->singleton($name, function ($app) use ($name) {
            $items = [];
            foreach (File::allFiles(base_path($name)) as $splFileInfo) {
                $path = $splFileInfo->getRealPath();
                $items[$splFileInfo->getFilenameWithoutExtension()] = array_merge(require $path, []);
            }
            return new Repository($items);
        });
        $this->app->instance($name, $this->app->$name);
    }
}
