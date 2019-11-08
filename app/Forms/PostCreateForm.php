<?php


namespace App\Forms;


use App\Contracts\Form;

class PostCreateForm implements Form
{
    private CONST FORM_NAME = 'PostCreateForm';
    private $data = [];
    private $errors = [];

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }

    public function getFields(): array
    {
        return [
            'title'   => 'required',
            'preview' => 'required',
            'content' => 'required',
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validate(): bool
    {
        foreach ($this->getFields() as $key => $value) {
            if ($value === 'required' && !array_key_exists($key,$this->data) || $this->data[$key] === '') {
                $this->errors[$key] = "Поле $key обязательно для заполнения";
            }
        }

        if (count($this->errors)) {
            return false;
        }

        return true;
    }

    public function load(array $request): void
    {
        $data        = isset($request[self::FORM_NAME]) ? $request[self::FORM_NAME] : [];
        $clearedData = $this->clearData($data);
        $this->data  = $clearedData;
    }

    public function clearData(array $data): array
    {
        return array_map(function ($item) {
            return strip_tags($item);
        }, $data);
    }

    public function getData(): array
    {
        return $this->data;
    }
}