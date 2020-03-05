<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Console\Stub;

use Illuminate\Support\Str;
use ShibuyaKosuke\LaravelLanguageMysqlComment\Models\Table;

/**
 * Class Stub
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Console\Stub
 */
class Stub
{
    public const CONTROLLER = 1;
    public const MODEL = 2;
    public const FORM_REQUEST = 3;

    /**
     * @var Table
     */
    private $table;

    /**
     * @var array
     */
    private $variables;

    /**
     * @var string
     */
    private $stub;

    public function __construct(Table $table, $stub)
    {
        $this->table = $table;
        $this->stub = $stub;

        $this->variables = [
            'DummyTable' => $table->TABLE_NAME,
            'DummyModelClass' => $table->model_name,
            'DummyModelVariable' => '$' . Str::snake(Str::singular($table->TABLE_NAME)),
            'DummyModelsVariable' => '$' . $table->TABLE_NAME,
            'DummyFormRequestClass' => $table->model_name . 'FormRequest',
            'DummyControllerClass' => $table->model_name . 'Controller',
        ];
    }

    /**
     * @return string|string[]
     */
    public function getStub()
    {
        $contents = '';
        switch ($this->stub) {
            case self::CONTROLLER:
                $contents = file_get_contents(__DIR__ . '/../stubs/controller.model.stub');
            case self::MODEL:
                $contents = file_get_contents(__DIR__ . '/../stubs/model.stub');
            case self::FORM_REQUEST:
                $contents = file_get_contents(__DIR__ . '/../stubs/request.stub');
        }
        return str_replace(array_keys($this->variables), array_values($this->variables), $contents);
    }
}