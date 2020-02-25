<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Services;

use Illuminate\Support\Collection;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Column;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Table;

class ColumnTranslator
{
    private $tables;
    private $buffer = [];

    public function __construct(Collection $tables)
    {
        $this->tables = $tables;
    }

    private function filename()
    {
        return resource_path('lang/ja/columns.php');
    }

    public function make()
    {
        $this->tables->each(function (Table $table) {
            $table->columns->each(function (Column $column) {
                $this->buffer[$column->TABLE_NAME][$column->COLUMN_NAME] = $column->COLUMN_COMMENT;
            });
        });
    }
}