<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Models;

use Illuminate\Database\Eloquent\Model;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Scopes\OwnDatabaseScope;

/**
 * Class InformationSchema
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Models
 */
abstract class InformationSchema extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OwnDatabaseScope());
    }

}