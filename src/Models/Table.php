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
        return $this->hasMany(Column::class, 'TABLE_NAME', 'TABLE_NAME');
    }

    /**
     * @return HasMany KeyColumnUsage[]
     */
    public function keyColumnUsage(): HasMany
    {
        return $this->hasMany(KeyColumnUsage::class, 'TABLE_NAME', 'TABLE_NAME')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->whereNotNull('REFERENCED_COLUMN_NAME');
    }

    /**
     * @inheritDoc
     */
    public function getQueueableRelations()
    {
        return [];
    }
}
