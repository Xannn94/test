<?php


namespace App\Contracts;


interface Entity
{
    public function loadData(array $data): Entity;

    public function tableName(): string;

    public function fillable(): array;
}