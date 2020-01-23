<?php

namespace onlinetests\DB;

class Delete extends Query
{

    protected $parts = [
        'from' => 'DELETE FROM',
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
