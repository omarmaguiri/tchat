<?php

namespace Core\Repository;


class Repository
{
    /**
     * @var \PDO
     */
    protected $pdo;

    function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

}