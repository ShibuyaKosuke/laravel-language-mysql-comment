<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Services;

use Illuminate\Support\Collection;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Column;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Table;

/**
 * Class ColumnTranslator
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Services
 */
class ColumnTranslator extends Translator
{
    private $tables;

    public function __construct(Collection $tables)
    {
        $this->tables = $tables;
    }

    protected function filename(): string
    {
        return resource_path(sprintf('lang/%s/columns.php', app()->getLocale()));
    }

    /**
     * @return bool
     */
    public function make(): bool
    {
        $this->tables->each(function (Table $table) {
            $table->columns->each(function (Column $column) {
                $this->buffer[$column->TABLE_NAME][$column->COLUMN_NAME] = $column->COLUMN_COMMENT;
            });
        });
        return file_put_contents($this->filename(), $this->code()) !== false;
    }
}
