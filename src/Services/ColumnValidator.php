<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Services;

use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Column;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Table;

/**
 * Class ColumnValidator
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Services
 */
class ColumnValidator
{
    private $table;
    private $rules = [];

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * @return bool|string|string[]
     */
    public function make()
    {
        if (!file_exists(base_path('rules'))) {
            mkdir(base_path('rules'));
        }

        $this->table->columns->each(function (Column $column) {
            $this->nullable($column);
            $this->dateType($column);
            $this->maxLength($column);
            $this->exists($column);
        });

        $file = $this->filename();
        if (file_put_contents($file, $this->parse())) {
            return str_replace(base_path() . '/', '', $file);
        }
        return false;
    }

    /**
     * @param Column $column
     */
    private function nullable(Column $column)
    {
        $this->rules[$column->COLUMN_NAME][] = ($column->is_required) ? 'required' : 'nullable';
    }

    /**
     * @param Column $column
     */
    private function dateType(Column $column)
    {
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
    }

    /**
     * @param Column $column
     */
    private function maxLength(Column $column)
    {
        if ($column->max_length) {
            $this->rules[$column->COLUMN_NAME][] = sprintf('max:%d', $column->max_length);
        }
    }

    /**
     * @param Column $column
     */
    private function exists(Column $column)
    {
        $key_column_usage = $column->keyColumnUsage;
        if ($key_column_usage) {
            $this->rules[$column->COLUMN_NAME][] = sprintf(
                'exists:%s,%s',
                $key_column_usage->REFERENCED_TABLE_NAME,
                $key_column_usage->REFERENCED_COLUMN_NAME
            );
        }
    }

    /**
     * @return string
     */
    protected function filename(): string
    {
        return base_path(sprintf('rules/%s.php', $this->table->TABLE_NAME));
    }

    /**
     * @return string
     */
    public function parse()
    {
        $indent = '    ';
        $columns = array_map(function ($rule) {
            return '[' . implode(', ', array_map(function ($val) {
                    return sprintf('\'%s\'', $val);
                }, $rule)) . '],';
        }, $this->rules);

        $lines = [];
        foreach ($columns as $col => $val) {
            $lines[] = sprintf($indent . '\'%s\' => %s', $col, $val);
        }
        return implode(PHP_EOL, ['<?php', '', 'return [', implode(PHP_EOL, $lines), '];']);
    }

    private function nullableOrRequired(Column $column)
    {
        $this->rules[$column->COLUMN_NAME][] = ($column->is_required) ? 'required' : 'nullable';
    }

    private function dataType(Column $column)
    {
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
    }
}
