<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http;

class ErrorResponse extends AbstractResponse {
    protected const SUCCESS = false;
// Неуспешный ответ содержит строку с причиной неуспеха,
// по умолчанию - 'Something goes wrong'
    public function __construct(
        private readonly string $reason = 'Something goes wrong'
    ) {
    }
// Реализация абстрактного метода
// родительского класса
    protected function payload(): array
    {
        return ['reason' => $this->reason];
    }
}