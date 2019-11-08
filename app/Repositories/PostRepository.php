<?php


namespace App\Repositories;


use App\Contracts\Entity;
use App\Contracts\Repository;
use App\Entities\Post;
use NilPortugues\Sql\QueryBuilder\Builder\BuilderInterface;
use NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder;
use PDO;

class PostRepository implements Repository
{
    /**
     * @var GenericBuilder
     */
    private $builder;
    private $db;

    public function __construct(BuilderInterface $builder, PDO $db)
    {
        $this->builder = $builder;
        $this->db      = $db;
    }

    /**
     * @return array
     */
    public function index(): array
    {
        $sql       = $this->builder->select($this->getEntity()->tableName())->getSql();
        $statement = $this->db->prepare($sql);

        if (!$statement->execute()) {
            return [];
        }

        $entityClass = get_class($this->getEntity());

        return $statement->fetchAll(PDO::FETCH_CLASS, $entityClass);
    }

    /**
     * @param array $data
     *
     * @return Entity
     */
    public function save(array $data): Entity
    {
        $entity = $this->getEntity()->loadData($data);
        $query  = $this->builder->insert($entity->tableName(), $entity->fillable());
        $sql    = $this->builder->write($query);
        $values = $this->builder->getValues();

        if ($this->db->prepare($sql)->execute($values)) {
            $id = $this->db->lastInsertId();

            return $this->find($id);
        }

        return $entity;
    }

    /**
     * @param array  $data
     * @param Entity $entity
     *
     * @return Entity
     */
    public function update(array $data, Entity $entity): Entity
    {
        $entity = $this->getEntity()->loadData($data);
        $query  = $this->builder->update()
            ->setTable($entity->tableName())
            ->setValues($entity->fillable())
            ->where()
            ->equals('id', $entity->getId())
            ->end();
        $sql    = $this->builder->write($query);
        $values = $this->builder->getValues();
        $this->db->prepare($sql)->execute($values);

        return $entity;
    }

    /**
     * @param $id
     */
    public function delete($id): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id): ?Entity
    {
        $query = $this->builder->select($this->getEntity()->tableName())
            ->where()
            ->equals('id', $id)->end();

        $sql       = $this->builder->write($query);
        $values    = $this->builder->getValues();
        $statement = $this->db->prepare($sql);
        if (!$statement->execute($values)) {
            return null;
        }

        $entityClass = get_class($this->getEntity());
        return $statement->fetchObject($entityClass);
    }

    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return new Post();
    }
}