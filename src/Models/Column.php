<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Column
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Models
 * @property string TABLE_CATALOG
 * @property string TABLE_SCHEMA
 * @property string TABLE_NAME
 * @property string COLUMN_NAME
 * @property int ORDINAL_POSITION
 * @property string COLUMN_DEFAULT
 * @property string IS_NULLABLE
 * @property string DATA_TYPE
 * @property int CHARACTER_MAXIMUM_LENGTH
 * @property int CHARACTER_OCTET_LENGTH
 * @property int NUMERIC_PRECISION
 * @property int NUMERIC_SCALE
 * @property int DATETIME_PRECISION
 * @property string CHARACTER_SET_NAME
 * @property string COLLATION_NAME
 * @property string COLUMN_TYPE
 * @property string COLUMN_KEY
 * @property string EXTRA
 * @property string PRIVILEGES
 * @property string COLUMN_COMMENT
 * @property string GENERATION_EXPRESSION
 */
class Column extends InformationSchema
{
    protected $table = 'information_schema.columns';

    /**
     * @return bool
     */
    public function getPrimaryKeyAttribute(): bool
    {
        return $this->COLUMN_KEY === 'PRI';
    }

    public function getHasIndexAttribute(): bool
    {
        return $this->COLUMN_KEY != '';
    }

    /**
     * @return BelongsTo Table
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'TABLE_NAME', 'TABLE_NAME');
    }

    /**
     * @return BelongsTo KeyColumnUsage
     */
    public function keyColumnUsage()
    {
        return $this->belongsTo(KeyColumnUsage::class, 'COLUMN_NAME', 'COLUMN_NAME')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->whereNotNull('REFERENCED_COLUMN_NAME');
    }

    public function hasManyColumns(): HasMany
    {
        return $this->hasMany(KeyColumnUsage::class, 'REFERENCED_COLUMN_NAME', 'COLUMN_NAME')
            ->where('REFERENCED_TABLE_NAME', $this->TABLE_NAME);
    }

    /**
     * is_required
     * @return bool
     */
    public function getIsRequiredAttribute()
    {
        return $this->IS_NULLABLE === 'NO';
    }

    /**
     * is_integer
     * @return bool
     */
    public function getIsIntegerAttribute(): bool
    {
        $data_type = $this->DATA_TYPE;
        if (strpos($data_type, 'int') !== false) {
            return true;
        }
        return false;
    }

    /**
     * is_numeric
     * @return bool
     */
    public function getIsNumericAttribute(): bool
    {
        $data_type = $this->DATA_TYPE;
        if (strpos($data_type, 'int') !== false) {
            return true;
        }
        switch ($data_type) {
            case 'float':
            case 'decimal':
            case 'double':
                return true;
            default:
                return false;
        }
    }

    /**
     * is_string
     * @return bool
     */
    public function getIsStringAttribute(): bool
    {
        $data_type = $this->DATA_TYPE;
        switch ($data_type) {
            case 'varchar':
            case 'char':
            case 'longtext':
            case 'text':
            case 'mediumtext':
                return true;
            default:
                return false;
        }
    }

    /**
     * is_date
     * @return bool
     */
    public function getIsDateAttribute(): bool
    {
        $data_type = $this->DATA_TYPE;
        switch ($data_type) {
            case 'timestamp':
            case 'datetime':
            case 'date':
            case 'time':
                return true;
            default:
                return false;
        }
    }

    /**
     * is_datetime
     * @return bool
     */
    public function getIsDatetimeAttribute(): bool
    {
        $data_type = $this->DATA_TYPE;
        switch ($data_type) {
            case 'timestamp':
            case 'datetime':
                return true;
            default:
                return false;
        }
    }

    /**
     * max_length
     * @return int|null
     */
    public function getMaxLengthAttribute(): ?int
    {
        if ($this->CHARACTER_MAXIMUM_LENGTH) {
            return $this->CHARACTER_MAXIMUM_LENGTH;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getQueueableRelations()
    {
        return [];
    }
}
