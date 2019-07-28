<?php declare(strict_types=1);

namespace App\Resources\Application;

final class AttributeTypeId
{
    private $typeId;

    private function __construct(string $typeId)
    {
        $this->typeId = $typeId;
    }

    public static function uuid(): self
    {
        return new self('uuid');
    }

    public static function dateTime(): self
    {
        return new self('datetime');
    }

    public static function emailAddress(): self
    {
        return new self('emailAddress');
    }

    public static function text(): self
    {
        return new self('text');
    }

    public static function commandObject(): self
    {
        return new self('commandObject');
    }

    public static function eventPayload(): self
    {
        return new self('eventPayload');
    }

    public static function authUserPayload(): self
    {
        return new self('authUserPayload');
    }
}