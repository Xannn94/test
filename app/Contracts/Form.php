<?php


namespace App\Contracts;


interface Form
{
    public function getFormName(): string;

    public function validate(): bool;

    public function getFields(): array;

    public function load(array $request): void;
}