<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Console\Services;

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

    /**
     * Stub constructor.
     * @param Table $table
     * @param $stub
     */
    public function __construct(Table $table, $stub)
    {
        $this->table = $table;
        $this->stub = $stub;

        $this->variables = [
            'DummyNamespace' => $this->getNamespace(),
            'DummyFullModelClass' => $this->getFullModelClass(),
            'DummyClass' => $this->getClass(),
            'DummyTable' => $table->TABLE_NAME,
            'DummyModelClass' => $table->model_name,
            'DummyModelVariable' => Str::snake(Str::singular($table->TABLE_NAME)),
            'DummyModelsVariable' => $table->TABLE_NAME,
            'DummyFormRequestClass' => $table->model_name . 'FormRequest',
            'DummyControllerClass' => $table->model_name . 'Controller',
        ];
    }

    private function getFullModelClass()
    {
        return 'App\\Models\\' . $this->table->model_name;
    }

    private function getClass()
    {
        switch ($this->stub) {
            case self::CONTROLLER:
                return sprintf('%sController', $this->table->model_name);
            case self::MODEL:
                return $this->table->model_name;
            case self::FORM_REQUEST:
                return sprintf('%sFormRequest', $this->table->model_name);
        }
    }

    private function getNamespace()
    {
        switch ($this->stub) {
            case self::CONTROLLER:
                return 'App\Http\Controllers';
            case self::MODEL:
                return 'App\Models';
            case self::FORM_REQUEST:
                return 'App\Http\Requests';
        }
        return '';
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
                break;
            case self::MODEL:
                $contents = file_get_contents(__DIR__ . '/../stubs/model.stub');
                break;
            case self::FORM_REQUEST:
                $contents = file_get_contents(__DIR__ . '/../stubs/request.stub');
                break;
        }
        return str_replace(array_keys($this->variables), array_values($this->variables), $contents);
    }
}