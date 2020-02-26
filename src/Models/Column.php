<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Column
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Models
 */
class Column extends InformationSchema
{
    protected $table = 'information_schema.columns';

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

    /**
     * is_required
     * @return bool
     */
    public function getIsRequiredAttribute()
    {
        return $this->getAttribute('IS_NULLABLE') === 'NO';
    }

    /**
     * data_type
     * @return string
     */
    public function getDataTypeAttribute(): string
    {
        return $this->getAttribute('DATA_TYPE');
    }

    /**
     * is_integer
     * @return bool
     */
    public function getIsIntegerAttribute(): bool
    {
        $data_type = $this->getAttribute('DATA_TYPE');
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
        $data_type = $this->getAttribute('DATA_TYPE');
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
        $data_type = $this->getAttribute('DATA_TYPE');
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
        $data_type = $this->getAttribute('DATA_TYPE');
        switch ($data_type) {
            case 'timestamp':
            case 'datetime':
            case 'date':
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
        $data_type = $this->getAttribute('DATA_TYPE');
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
}
