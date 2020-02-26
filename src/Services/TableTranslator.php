<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Services;

use Illuminate\Support\Collection;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Table;

class TableTranslator extends Translator
{
    private $tables;

    public function __construct(Collection $tables)
    {
        $this->tables = $tables;
    }

    protected function filename(): string
    {
        return resource_path(sprintf('lang/%s/tables.php', app()->getLocale()));
    }

    /**
     * @return bool
     */
    public function make(): bool
    {
        $this->tables->each(function (Table $table) {
            $this->buffer[$table->TABLE_NAME] = $table->TABLE_COMMENT;
        });
        return file_put_contents($this->filename(), $this->code()) !== false;
    }
}
