<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Services;

use Illuminate\Support\Collection;

class ColumnValidator
{
    private $columns;

    public function __construct(Collection $columns)
    {
        $this->columns = $columns;
    }

    public function make()
    {
    }
}
