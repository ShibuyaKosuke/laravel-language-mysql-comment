<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Console;

use Illuminate\Console\Command;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Table;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Services\ColumnTranslator;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Services\TableTranslator;

class LangPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trans:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    public function handle(): void
    {
        $tables = Table::query()
            ->with(['columns', 'columns.key_column_usage'])
            ->get()
            ->reject(function (Table $table) {
                return empty($table->TABLE_COMMENT);
            });

        (new TableTranslator($tables))->make();
        (new ColumnTranslator($tables))->make();
    }
}