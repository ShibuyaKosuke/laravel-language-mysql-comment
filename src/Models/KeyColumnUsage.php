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

    /**
     * @return BelongsTo Table
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'TABLE_NAME', 'TABLE_NAME');
    }

    /**
     * @inheritDoc
     */
    public function getQueueableRelations()
    {
        return [];
    }
}
