<?php

namespace onlinetests\DB;

class Result
{
    /**
     * Error code number
     * -1 undefined
     * 0 success
     * 1 error
     * @var int
     */
    private $errCode;

    private $errMessage;

    /**
     * Affcted rows count
     * -1 undefined
     * 0-n rows count
     * @var int
     */
    private $rowCount;

    /**
     * Last inserted id
     * -1 undefined
     * 0-n id
     * @var int
     */
    private $insertId;

    /**
     * Data by SQL Select
     * @var array
     */
    private $data;

    public function __construct()
    {
        $this->errCode = -1;
        $this->errMessage = '';
        $this->rowCount = -1;
        $this->insertId = -1;
        $this->data = [];
    }

    public function setErr(int $errCode, string $errMessage = ''): Result
    {
        $this->errCode = $errCode;
        $this->errMessage = $errMessage;
        return $this;
    }

    public function getErrCode(): int
    {
        return $this->errCode;
    }

    public function getErrMessage(): string
    {
        return $this->errMessage;
    }

    public function isSuccess(): bool
    {
        return $this->errCode === 0;
    }

    public function setRowCount(int $rowCount): Result
    {
        $this->rowCount = $rowCount;
        return $this;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function setInsertId(int $insertId): Result
    {
        $this->insertId = $insertId;
        return $this;
    }

    public function getInsertId(): int
    {
        return $this->insertId;
    }

    public function setData(array $data): Result
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
