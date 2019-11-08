<?php


namespace App;


class MysqlPdo extends \PDO
{
    public function __construct()
    {
        $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=test';

        parent::__construct($dsn, 'root');
    }
}