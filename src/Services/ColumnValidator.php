<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Services;

use Illuminate\Support\Collection;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Column;

class ColumnValidator
{
    private $columns;
    private $rules = [];

    public function __construct(Collection $columns)
    {
        $this->columns = $columns;
    }

    public function make()
    {
        $this->columns->each(function (Column $column) {

            // required or nullable
            $this->rules[$column->COLUMN_NAME][] = ($column->is_required) ? 'required' : 'nullable';

            // data_type
            if ($column->is_datetime) {
                $this->rules[$column->COLUMN_NAME][] = 'datetime';
            } elseif ($column->is_date) {
                $this->rules[$column->COLUMN_NAME][] = 'date';
            } elseif ($column->is_integer) {
                $this->rules[$column->COLUMN_NAME][] = 'integer';
            } elseif ($column->is_numeric) {
                $this->rules[$column->COLUMN_NAME][] = 'numeric';
            } elseif ($column->is_string) {
                $this->rules[$column->COLUMN_NAME][] = 'string';
            }

            // max
            if ($column->max_length) {
                $this->rules[$column->COLUMN_NAME][] = sprintf('max:%d', $column->max_length);
            }

            $key_column_usage = $column->keyColumnUsage;
            if ($key_column_usage) {
                $tbl = $key_column_usage->REFERENCED_TABLE_NAME;
                $col = $key_column_usage->REFERENCED_COLUMN_NAME;
                $this->rules[$column->COLUMN_NAME][] = sprintf('exists:%s,%s', $tbl, $col);
            }
        });
    }
}
