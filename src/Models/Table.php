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
     * @return string
     */
    public function getControllerNameAttribute()
    {
        return sprintf('%sController', $this->model_name);
    }

    /**
     * @return string
     */
    public function getFormRequestNameAttribute()
    {
        return sprintf('%sFormRequest', $this->model_name);
    }

    /**
     * @return string
     */
    public function getFactoryNameAttribute()
    {
        return sprintf('%sFactory', $this->model_name);
    }

    /**
     * @return string
     */
    public function getSeederNameAttribute()
    {
        return sprintf('%sSeeder', $this->model_name);
    }

    /**
     * @return bool
     */
    public function getIsLinkAttribute()
    {
        return strpos($this->TABLE_COMMENT, 'link:') === 0;
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

    /**
     * @inheritDoc
     */
    public function getQueueableRelations()
    {
        return [];
    }
}
