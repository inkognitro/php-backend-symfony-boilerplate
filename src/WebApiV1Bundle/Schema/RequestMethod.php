<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class RequestMethod
{
    private $method;

    private function __construct(string $method)
    {
        $this->method = $method;
    }

    public static function get(): self
    {
        return new self('GET');
    }

    public static function post(): self
    {
        return new self('POST');
    }

    public static function put(): self
    {
        return new self('PUT');
    }

    public static function delete(): self
    {
        return new self('DELETE');
    }

    public function toString(): string
    {
        return $this->method;
    }
}