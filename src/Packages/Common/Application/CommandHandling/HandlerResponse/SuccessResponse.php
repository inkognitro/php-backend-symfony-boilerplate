<?php declare(strict_types=1);

namespace App\Packages\Common\Application\CommandHandling\HandlerResponse;

use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\Validation\MessageBag;

final class SuccessResponse implements HandlerResponse
{
    private $data;
    private $warnings;

    public function __construct(array $data, MessageBag $warnings)
    {
        $this->data = $data;
        $this->warnings = $warnings;
    }

    public static function fromData(array $data): self
    {
        return new self($data, new MessageBag());
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }
}