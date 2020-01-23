<?php

namespace onlinetests\DB;

class Select extends Query
{

    protected $parts = [
        'select' => 'SELECT',
        'from' => 'FROM',
        'where' => 'WHERE',
        'group' => 'GROUP BY',
        'having' => 'HAVING',
        'order' => 'ORDER BY',
        'limit' => 'LIMIT'
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
