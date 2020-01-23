<?php

namespace onlinetests\DB;

use onlinetests\Utils;

class Driver implements IDriver
{

    /**
     * Main PDO connection link
     * @var \PDO 
     */
    private static $connection = null;

    /**
     * Basic configuration array
     * @var array 
     */
    private static $settings = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ];

    /**
     * Last Result
     * @var Result
     */
    private static $lastResult;

    /**
     * Argument prefix
     * @var string
     */
    private static $argsPrefix = ':';

    /**
     * Connect to MySql using PDO
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @return void
     */
    public static function connect(string $host, string $username, string $password, string $dbname): void
    {
        try {
            self::$connection = new \PDO("mysql:host=$host;dbname=$dbname", $username, $password, self::$settings);
        } catch (\PDOException $e) {
            self::$connection = null;
        }
    }

    /**
     * Close the connection
     * @return void
     */
    public static function close(): void
    {
        self::$connection = null;
    }

    /**
     * Processing the SQL query
     * @param Query $query
     * @return Result
     */
    public static function query(Query $query): Result
    {
        if (!self::isConnected()) {
            self::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
        self::$lastResult = new Result();
        try {
            $queryResult = self::$connection->prepare($query->render());
        } catch (\PDOException $e) {
            return self::$lastResult->setErr(1, $e->getMessage());
        }
        $args = $query->getArgs();
        if (!empty(self::$argsPrefix) && !empty($args)) {
            $args = Utils\Arrays::setKeyPrefix($args, self::$argsPrefix);
        }
        try {
            $queryResult->execute(Utils\Arrays::emptyStringsToNull($args));
        } catch (\PDOException $e) {
            return self::$lastResult->setErr(1, $e->getMessage());
        }
        $errInfo = $queryResult->errorInfo();
        if (!isset($errInfo[0]) || $errInfo[0] !== '00000') {
            return self::$lastResult->setErr(1, isset($errInfo[2]) ? $errInfo[2] : 'Query execute error.');
        }
        self::$lastResult->setRowCount($queryResult->rowCount());
        if ($query instanceof Insert) {
            self::$lastResult->setInsertId(self::$connection->lastInsertId());
        }
        if ($query instanceof Select) {
            self::$lastResult->setData($queryResult->fetchAll(\PDO::FETCH_ASSOC));
        }
        return self::$lastResult->setErr(0);
    }

    /**
     * Get Last Result
     * @return Result
     */
    public static function getLastResult(): Result
    {
        return self::$lastResult;
    }

    /**
     * Checking the connection to the database
     * @return bool
     */
    public static function isConnected(): bool
    {
        return (self::$connection instanceof \PDO);
    }
}
