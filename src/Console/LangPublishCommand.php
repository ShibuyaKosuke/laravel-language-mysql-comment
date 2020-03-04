<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Table;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Services\ColumnTranslator;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Services\ColumnValidator;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Services\TableTranslator;

/**
 * Class LangPublishCommand
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Console
 */
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
            ->with(['columns', 'columns.keyColumnUsage']);

        $tables->get()->each(function (Table $table) {
            $file = (new ColumnValidator($table))->make();
            $this->info('Success: ' . $file);

            Artisan::call(sprintf('make:policy %sPolicy --model=%s', $table->model_name, $table->model_name));
            Artisan::call(sprintf('make:request %sFormRequest', $table->model_name));
        });

        $tables->where('TABLE_COMMENT', '<>', '');

        if ((new TableTranslator($tables->get()))->make()) {
            $this->info('Success: Resources/lang/tables.php');
        }
        if ((new ColumnTranslator($tables->get()))->make()) {
            $this->info('Success: Resources/lang/columns.php');
        }
    }
}
