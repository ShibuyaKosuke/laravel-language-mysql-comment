<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Services;

use Illuminate\Support\Str;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Column;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Table;

class ColumnValidator
{
    private $table;
    private $rules = [];

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * @return bool|void
     */
    public function make()
    {
        $this->table->columns->each(function (Column $column) {
            $this->nullableOrRequired($column);
            $this->dataType($column);
            $this->maxLength($column);
            $this->exists($column);
        });

        $contents = $this->parse();
        $file = $this->filename();
        file_put_contents($file, $contents);
    }

    protected function filename(): string
    {
        return base_path(
            sprintf('rules/%s.php',
                $this->table->TABLE_NAME
            )
        );
    }

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

    private function maxLength(Column $column)
    {
        if ($column->max_length) {
            $this->rules[$column->COLUMN_NAME][] = sprintf('max:%d', $column->max_length);
        }
    }

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
}
