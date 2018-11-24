<?php declare(strict_types=1);

namespace App\Resources\Common\Application\HandlerResponse;

use App\Packages\Common\Application\Command\Validation\MessageBag;
use App\Packages\Common\Application\HandlerResponse\Success;
use App\Resources\Common\Application\Resource;

final class ChangeSuccessResponse implements Success
{
    private $resource;
    private $warnings;
    
    public function __construct(Resource $resource, MessageBag $warnings)
    {
        $this->resource = $resource;
        $this->warnings = $warnings;
    }

    public static function fromResource(Resource $resource): self
    {
        return new self($resource, new MessageBag());
    }

    public function getResource(): Resource
    {
        return $this->resource;
    }

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }
}