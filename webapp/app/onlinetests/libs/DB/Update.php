<?php

namespace onlinetests\DB;

use onlinetests\Utils;

class Update extends Query
{

    protected $parts = [
        'table' => 'UPDATE',
        'set' => 'SET',
        'where' => 'WHERE'
    ];

    public function __call($name, $arguments)
    {
        return $this->makeOperation($name, $arguments);
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
