<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query\QueueJob\Attributes;

use App\Packages\Common\Application\Query\Attribute;
use App\Packages\Common\Application\Command\Command as CommandBusCommand;
use App\Packages\Common\Application\Query\AttributeTypeId;

final class Command implements Attribute
{
    private $command;

    private function __construct(CommandBusCommand $command)
    {
        $this->command = $command;
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::eventPayload();
    }

    public static function getPayloadKey(): string
    {
        return 'command';
    }

    public static function fromCommand(CommandBusCommand $command): self
    {
        return new self($command);
    }

    public static function fromSerializedString(string $serialized): self
    {
        return new self(unserialize($serialized));
    }

    public function toSerializedString(): string
    {
        return serialize($this->command);
    }
}