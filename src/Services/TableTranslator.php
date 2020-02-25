<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Services;

use Illuminate\Support\Collection;

class TableTranslator
{
    private $tables;

    public function __construct(Collection $tables)
    {
        $this->tables = $tables;
    }

    private function filename()
    {
        return resource_path('lang/ja/tables.php');
    }

    public function make()
    {

    }
}