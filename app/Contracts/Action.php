<?php


namespace App\Contracts;


use NilPortugues\Sql\QueryBuilder\Builder\BuilderInterface;
use PDO;

interface Action
{
    public function run();

    public function getRepository(BuilderInterface $builder, PDO $db): Repository;
}