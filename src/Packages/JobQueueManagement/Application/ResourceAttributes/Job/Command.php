<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Application\ResourceAttributes\Job;

use App\Packages\Common\Application\ResourceAttributes\Attribute;
use App\Packages\Common\Application\Command\Command as CommandBusCommand;

final class Command implements Attribute
{
    private $command;

    private function __construct(CommandBusCommand $command)
    {
        $this->command = $command;
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