<?php

namespace onlinetests\DB;

use onlinetests\Utils;

class Insert extends Query
{

    protected $parts = [
        'table' => 'INSERT INTO'
    ];

    public function __call($name, $arguments)
    {
        return $this->makeOperation($name, $arguments);
    }

    public function render(): string
    {
        if (empty($this->parts['table']) || empty($this->args)) {
            return '';
        }
        return $this->parts['table'] . " `" . $this->query['table'] . "` (" .
            Utils\Arrays::toString($this->args, ', ', 'key', '`', '`') . ") VALUES (" .
            Utils\Arrays::toString($this->args, ', ', 'key', ':') . ")";
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
