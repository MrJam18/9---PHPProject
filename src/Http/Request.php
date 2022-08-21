<?php
declare(strict_types=1);

namespace Jam\PhpProject\Http;

use Jam\PhpProject\Exceptions\HTTPException;

class Request
{
    public function __construct(
        private readonly array $server,
        private readonly array $get,
        private readonly string $body
    )
    {
    }

    /**
     * @throws HTTPException
     */
    public function path(): string
    {
// В суперглобальном массиве $_SERVER
// значение URI хранится под ключом REQUEST_URI
        if (!array_key_exists('REQUEST_URI', $this->server)) {
// Если мы не можем получить URI - бросаем исключение
            throw new HttpException('Cannot get path from the request');
        }
// Используем встроенную в PHP функцию parse_url
        $components = parse_url($this->server['REQUEST_URI']);
        if (!is_array($components) || !array_key_exists('path', $components)) {
// Если мы не можем получить путь - бросаем исключение
            throw new HttpException('Cannot get path from the request');
        }
        return $components['path'];
    }

// Метод для получения значения
// определённого заголовка
    /**
     * @throws HTTPException
     */
    public function header(string $header): string
    {
        // В суперглобальном массиве $_SERVER
        // имена заголовков имеют префикс 'HTTP_',
        // а знаки подчёркивания заменены на минусы
        $headerName = mb_strtoupper("http_". str_replace('-', '_', $header));
        if (!array_key_exists($headerName, $this->server)) {
            // Если нет такого заголовка - бросаем исключение
            throw new HttpException("No such header in the request: $header");
        }
        $value = trim($this->server[$headerName]);
        if (empty($value)) {
        // Если значение заголовка пусто - бросаем исключение
            throw new HttpException("Empty header in the request: $header");
        }
        return $value;
    }

    /**
     * @throws HTTPException
     */
    public function query(string $param): string
    {
        if (!array_key_exists($param, $this->get)) {
            throw new HttpException(
                "No such query param in the request: $param"
            );
        }
        $value = trim($this->get[$param]);
        if (empty($value)) {
            throw new HttpException(
                "Empty query param in the request: $param"
            );
        }
        return $value;
    }


    /**
     * @throws HTTPException
     */
    public function jsonBody(): array
    {
        try {
// Пытаемся декодировать json
            $data = json_decode(
                $this->body,
// Декодируем в ассоциативный массив
                associative: true,
// Бросаем исключение при ошибке
                flags: JSON_THROW_ON_ERROR
            );
        } catch (\JsonException) {
            throw new HttpException("Cannot decode json body");
        }
        if (!is_array($data)) {
            throw new HttpException("Not an array/object in json body");
        }
        return $data;
    }

    /**
     * @throws HTTPException
     */
    public function jsonBodyField(string $field): mixed
    {
        $data = $this->jsonBody();
        if (!array_key_exists($field, $data)) {
            throw new HttpException("No such field: $field");
        }
        if (empty($data[$field])) {
            throw new HttpException("Empty field: $field");
        }
        return $data[$field];
    }


}