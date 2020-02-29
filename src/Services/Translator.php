<?php

namespace ShibuyaKosuke\LaravelLanguageMysqlComment\Services;

/**
 * Class Translator
 * @package ShibuyaKosuke\LaravelLanguageMysqlComment\Services
 */
abstract class Translator
{
    protected const INDENT = '    ';

    protected $buffer = [];

    abstract protected function filename(): string;

    abstract public function make(): bool;

    private function indent($i)
    {
        return str_repeat(self::INDENT, $i);
    }

    protected function code()
    {
        $temp = [];
        $temp[] = '<?php';
        $temp[] = '';
        $temp[] = 'return [';
        $temp[] = $this->indent(1) . $this->parse($this->buffer);
        $temp[] = '];';
        $temp[] = '';
        return implode(PHP_EOL, $temp);
    }

    protected function parse(array $array, int $indent = 0)
    {
        $temp = [];
        $indent++;
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $temp[] = $this->indent($indent - 1) . sprintf('\'%s\' => [', $key);
                $temp[] = $this->parse($value, $indent);
                $temp[] = $this->indent($indent - 1) . '],';
                continue;
            }
            $temp[] = sprintf('\'%s\' => \'%s\',', $key, $value);
        }
        return $this->indent($indent - 1) . implode(PHP_EOL . $this->indent($indent), $temp);
    }
}
