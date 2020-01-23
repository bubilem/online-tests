<?php

namespace onlinetests\DB;

abstract class Query
{
    /**
     * Parts of query command
     * @var array
     */
    protected $parts = [];

    /**
     * Query array
     * @var array
     */
    protected $query;

    /**
     * Arguments array
     * @var array
     */
    protected $args;

    public function __construct()
    {
        $this->clear();
    }

    /**
     * Clear the query and arguments
     * @return void
     */
    public function clear(): void
    {
        $this->query = $this->args = [];
    }

    /**
     * Arguments setter
     * @param array $args
     * @return Query
     */
    public function setArgs(array $args): Query
    {
        $this->args = $args;
        return $this;
    }

    /**
     * Arguments getter
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * Run query via driver
     * @return Result
     */
    public function run(): Result
    {
        return Driver::query($this);
    }

    /**
     * Process supported operations (set, add, get, clr) on query parts
     * Support function for __call() in child classes
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    protected function makeOperation(string $name, array $arguments)
    {
        $typeOfMethod = strtolower(substr($name, 0, 3));
        if (in_array($typeOfMethod, array('set', 'add', 'get', 'clr'))) {
            $name = strtolower(substr($name, 3));
        } else {
            $typeOfMethod = 'set';
        }
        if (array_key_exists($name, $this->parts)) {
            switch ($name) {
                case 'select':
                case 'order':
                case 'group':
                    $separator = ', ';
                    break;
                case 'where':
                case 'having':
                    $separator = ' AND ';
                    break;
                case 'from':
                default:
                    $separator = ' ';
                    break;
            }
            switch ($typeOfMethod) {
                case 'set':
                    $value = implode($separator, $arguments);
                    if (!empty($value)) {
                        $this->query[$name] = $value;
                    }
                    break;
                case 'add':
                    $value = implode($separator, $arguments);
                    if (!empty($value)) {
                        if (isset($this->query[$name]) && !empty($this->query[$name])) {
                            $value = $this->query[$name] . $separator . $value;
                        }
                        $this->query[$name] = $value;
                    }
                    break;
                case 'get':
                    if (isset($this->query[$name])) {
                        return $this->query[$name];
                    } else {
                        return null;
                    }
                    break;
                case 'clr':
                    if (isset($this->query[$name])) {
                        unset($this->query[$name]);
                    }
                    break;
            }
        }
        return $this;
    }

    /**
     * Compilation SQL query string from individual query parts
     * @return string
     */
    public function render(): string
    {
        $queryString = '';
        foreach ($this->parts as $key => $val) {
            if (!empty($this->query[$key])) {
                $queryString .= (" " . $val . " " . $this->query[$key]);
            }
        }
        return trim($queryString);
    }
}
