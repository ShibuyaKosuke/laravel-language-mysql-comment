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

            $this->rules[$column->COLUMN_NAME][] = ($column->is_required) ? 'required' : 'nullable';

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

            if ($column->max_length) {
                $this->rules[$column->COLUMN_NAME][] = sprintf('max:%d', $column->max_length);
            }

            $key_column_usage = $column->keyColumnUsage;
            if ($key_column_usage) {
                $this->rules[$column->COLUMN_NAME][] = sprintf(
                    'exists:%s,%s',
                    $key_column_usage->REFERENCED_TABLE_NAME,
                    $key_column_usage->REFERENCED_COLUMN_NAME
                );
            }
        });

        $this->parse();
        dump($this->filename());
    }

    protected function filename(): string
    {
        return app_path(
            sprintf('Http/Controllers/Requests/%sFormRequest',
                Str::studly(Str::singular($this->table->TABLE_NAME))
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
        dump(implode(PHP_EOL, ['return [', implode(PHP_EOL, $lines), '];']));
    }
}
