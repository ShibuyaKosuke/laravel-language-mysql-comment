<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class KeyColumnUsage
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Models
 */
class KeyColumnUsage extends InformationSchema
{
    protected $table = 'information_schema.key_column_usage';

    protected $appends = [
        'table_column_name'
    ];

    public function getTableColumnName()
    {
        return sprintf('%s.%s', $this->TABLE_NAME, $this->COLUMN_NAME);
    }

    /**
     * @return BelongsTo Table
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'TABLE_NAME', 'TABLE_NAME')->withDefault();
    }

    /**
     * @return string|null
     */
    public function getBelongsToAttribute()
    {
        if (is_null($this->REFERENCED_TABLE_NAME) || is_null($this->REFERENCED_COLUMN_NAME)) {
            return null;
        }
        return sprintf('%s.%s', $this->REFERENCED_TABLE_NAME, $this->REFERENCED_COLUMN_NAME);
    }

    /**
     * @return string|null
     */
    public function getHasManyAttribute()
    {
        if (is_null($this->TABLE_NAME) || is_null($this->COLUMN_NAME)) {
            return null;
        }
        return sprintf('%s.%s', $this->TABLE_NAME, $this->COLUMN_NAME);
    }

    /**
     * @inheritDoc
     */
    public function getQueueableRelations()
    {
        return [];
    }
}
