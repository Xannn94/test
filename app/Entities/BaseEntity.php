<?php


namespace App\Entities;


use App\Contracts\Entity;

abstract class BaseEntity implements Entity
{
    public function loadData(array $data): Entity
    {
        foreach (array_keys(get_object_vars($this)) as $field) {
            if (isset($data[$field]) && $data[$field] !== null) {
                $this->{$field} = $data[$field];
            }
        }

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}