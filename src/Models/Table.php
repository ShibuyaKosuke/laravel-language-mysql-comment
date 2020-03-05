<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class Table
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Models
 */
class Table extends InformationSchema
{
    protected $table = 'information_schema.tables';

    /**
     * @return string
     */
    public function getModelNameAttribute()
    {
        return Str::studly(Str::singular($this->TABLE_NAME));
    }

    /**
     * @return HasMany Column[]
     */
    public function columns(): HasMany
    {
        return $this->hasMany(Column::class, 'TABLE_NAME', 'TABLE_NAME')
            ->addSelect(DB::raw('*, CONCAT(TABLE_NAME, \'.\' ,COLUMN_NAME) as `TABLE_COLUMN_NAME`'));
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

    public function getIsIntermediateTableAttribute($key)
    {
        $table_names = Table::all()->pluck('TABLE_NAME');
        if ($this->columns->pluck('COLUMN_NAME')->contains('id')) {
            return false;
        }
        $filtered = $this->columns->filter(function (Column $column) use ($table_names) {
            $table_name = Str::plural(
                str_replace('_id', '', $column->COLUMN_NAME)
            );
            return $table_names->contains($table_name);
        });
        return $filtered->count() == 2;
    }

    /**
     * @inheritDoc
     */
    public function getQueueableRelations()
    {
        return [];
    }
}
