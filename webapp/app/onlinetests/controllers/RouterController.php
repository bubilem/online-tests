<?php

namespace onlinetests;

class RouterController
{

    public static function create(): RouterController
    {
        return new RouterController();
    }

    public function run(): void
    {
        $insert = new DB\Insert();
        $insert->setTable('demo')->setArgs([
            'name' => 'SpaceX',
            'date' => Utils\DateTime::createNow()->toDatabase()
        ]);
        var_dump(strval($insert));
        //var_dump($insert->run());

        $result = (new DB\Update())
            ->setTable('demo')
            ->setSet('name = :name')
            ->setWhere('id = :id')
            ->setArgs(['id' => 1, 'name' => 'puma.cz'])
            ->run();
        var_dump($result);

        var_dump((new DB\Delete())->setFrom('demo')->setWhere('id = 6')->run());

        $q = new DB\Select();
        $q->setSelect("id");
        $q->setFrom("product")->setOrder("id");
        $q->addSelect("name, date");
        $q->setFrom("demo");
        var_dump($q->run());
    }
}
