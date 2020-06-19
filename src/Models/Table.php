<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Class Table
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Models
 *
 * @property string TABLE_CATALOG varchar
 * @property string TABLE_SCHEMA varchar
 * @property string TABLE_NAME varchar
 * @property string TABLE_TYPE varchar
 * @property string ENGINE varchar
 * @property int VERSION bigint
 * @property string ROW_FORMAT varchar
 * @property int TABLE_ROWS bigint
 * @property int AVG_ROW_LENGTH bigint
 * @property int DATA_LENGTH bigint
 * @property int MAX_DATA_LENGTH bigint
 * @property int INDEX_LENGTH bigint
 * @property int DATA_FREE bigint
 * @property int AUTO_INCREMENT bigint
 * @property string CREATE_TIME datetime
 * @property string UPDATE_TIME datetime
 * @property string CHECK_TIME datetime
 * @property string TABLE_COLLATION varchar
 * @property int CHECKSUM bigint
 * @property string CREATE_OPTIONS varchar
 * @property string TABLE_COMMENT varchar
 * @property string model_name
 * @property string controller_name
 * @property string factory_name
 * @property string seeder_name
 * @property string form_request_name
 * @property string is_link
 * @property KeyColumnUsage[] belongs_to
 * @property Table[] has_many
 * @property Boolean is_intermediate_table
 * @property Column[] columns
 * @property KeyColumnUsage[] keyColumnUsage
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
     * @param $key
     * @return bool
     */
    public function getIsIntermediateTableAttribute($key)
    {
        if ($this->columns->pluck('COLUMN_NAME')->contains('id')) {
            return false;
        }
        $filtered = $this->keyColumnUsage
            ->filter(function (KeyColumnUsage $keyColumnUsage) {
                return !is_null($keyColumnUsage->belongsTo);
            });
        return $filtered->count() == 2;
    }

    /**
     * @return KeyColumnUsage[]
     */
    public function getBelongsToAttribute()
    {
        return $this->keyColumnUsage;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|null
     */
    public function getHasManyAttribute()
    {
        $table_names = KeyColumnUsage::query()
            ->select('TABLE_NAME')
            ->where('REFERENCED_TABLE_NAME', $this->TABLE_NAME)
            ->whereNotIn('COLUMN_NAME', ['created_by', 'updated_by', 'deleted_by'])
            ->get()
            ->pluck('TABLE_NAME');
        return ($table_names) ? Table::query()->whereIn('TABLE_NAME', $table_names)->get() : null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|null
     */
    public function getBelongsToManyAttribute()
    {
        if (!$this->TABLE_COMMENT) {
            return null;
        }
        $tables = Table::query()
            ->where('TABLE_COMMENT', '<>', '')
            ->get()
            ->pluck('TABLE_NAME');

        $join = $tables->crossJoin($tables)
            ->map(function ($join) {
                return implode('_', array_map(function ($join) {
                    return Str::singular($join);
                }, $join));
            });

        $belongsToMany = KeyColumnUsage::query()
            ->whereIn('TABLE_NAME', function ($query) use ($join) {
                $query->from('information_schema.KEY_COLUMN_USAGE')
                    ->select('TABLE_NAME')
                    ->whereNotNull('REFERENCED_TABLE_NAME')
                    ->where('REFERENCED_TABLE_NAME', '=', $this->TABLE_NAME)
                    ->whereIn('TABLE_NAME', $join);
            })
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->where('REFERENCED_TABLE_NAME', '<>', $this->TABLE_NAME)
            ->get();

        $table_names = $belongsToMany->pluck('REFERENCED_TABLE_NAME') ?? [];
        return ($table_names) ? Table::query()->whereIn('TABLE_NAME', $table_names)->get() : null;
    }
}
