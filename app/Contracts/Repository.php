<?php


namespace App\Contracts;


interface Repository
{
    /**
     * @return Entity[]
     */
    public function index(): array;

    public function save(array $data): Entity;

    public function update(array $data, Entity $entity): Entity;

    public function delete($id): void;

    public function find(int $id): ?Entity;

    public function getEntity(): Entity;
}