<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class OwnDatabaseScope
 * @package Shibuyakosuke\LaravelLanguageMysqlComment\Scopes
 */
class OwnDatabaseScope implements Scope
{

    /**
     * @inheritDoc
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('table_schema', \app('db.connection')->getDatabaseName())
            ->where('table_name', '<>', 'migrations');
    }
}
