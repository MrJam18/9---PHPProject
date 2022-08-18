<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http;

class SuccessfulResponse extends AbstractResponse {
    protected const SUCCESS = true;
// Успешный ответ содержит массив с данными,
// по умолчанию - пустой
    public function __construct(
        private readonly array $data = []
    ) {
    }
// Реализация абстрактного метода
// родительского класса
    protected function payload(): array
    {
        return ['data' => $this->data];
    }

}