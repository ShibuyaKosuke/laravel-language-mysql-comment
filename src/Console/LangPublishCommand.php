<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Console;

use Illuminate\Console\Command;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Table;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Services\ColumnTranslator;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Services\ColumnValidator;
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
    protected $description = 'Output language files from DB column comments.';

    public function handle(): void
    {
        $tables = Table::query()
            ->with(['columns', 'columns.keyColumnUsage'])
            ->where('TABLE_COMMENT', '<>', '')
            ->get();

        $tables->each(function (Table $table) {
            (new ColumnValidator($table))->make();
        });

//        if ((new TableTranslator($tables))->make()) {
//            $this->info('Success: Resources/lang/tables.php');
//        }
//        if ((new ColumnTranslator($tables))->make()) {
//            $this->info('Success: Resources/lang/columns.php');
//        }
    }
}
