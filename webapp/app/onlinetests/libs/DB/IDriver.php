<?php

namespace onlinetests\DB;

interface IDriver
{
    /**
     * Connect to MySql using PDO
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @return void
     */
    public static function connect(string $host, string $username, string $password, string $dbname): void;

    /**
     * Close the connection
     * @return void
     */
    public static function close(): void;

    /**
     * Processing the SQL query
     * @param Query $query
     * @return Result
     */
    public static function query(Query $query): Result;

    /**
     * Get Last Result
     * @return Result
     */
    public static function getLastResult(): Result;

    /**
     * Checking the connection to the database
     * @return bool
     */
    public static function isConnected(): bool;
}
