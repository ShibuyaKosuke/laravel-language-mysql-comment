<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Helpers;

use Illuminate\Support\Facades\App;

/**
 * Class ValidationRule
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Helpers
 */
class ValidationRule
{
    /**
     * @return string|array|null
     */
    public static function all()
    {
        return App::get('rules')->all();
    }

    /**
     * @param string $key
     * @return array|string|null
     */
    public static function get(string $key)
    {
        return App::get('rules')->get($key);
    }
}
