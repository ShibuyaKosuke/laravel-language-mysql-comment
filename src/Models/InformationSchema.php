<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Models;

use Illuminate\Database\Eloquent\Model;
use Shibuyakosuke\LaravelLanguageMysqlComment\Scopes\OwnDatabaseScope;

/**
 * Class InformationSchema
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Models
 */
abstract class InformationSchema extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OwnDatabaseScope());
    }

}