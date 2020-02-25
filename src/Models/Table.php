<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Table
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Models
 */
class Table extends InformationSchema
{
    protected $table = 'information_schema.tables';

    /**
     * @return HasMany Column[]
     */
    public function columns(): HasMany
    {
        return $this->hasMany(Column::class, 'table_name', 'table_name');
    }

    /**
     * @return HasMany KeyColumnUsage[]
     */
    public function key_column_usages(): HasMany
    {
        return $this->hasMany(KeyColumnUsage::class, 'table_name', 'table_name');
    }


}