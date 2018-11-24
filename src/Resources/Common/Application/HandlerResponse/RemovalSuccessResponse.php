<?php declare(strict_types=1);

namespace App\Resources\Common\Application\HandlerResponse;

use App\Packages\Common\Application\Command\Validation\MessageBag;
use App\Packages\Common\Application\HandlerResponse\Success;
use App\Resources\Common\Application\Resource;

final class RemovalSuccessResponse implements Success
{
    private $resource;
    private $warnings;
    
    public function __construct(Resource $resource, MessageBag $warnings)
    {
        $this->resource = $resource;
        $this->warnings = $warnings;
    }

    /** @param $resource mixed */
    public static function fromResource($resource): self
    {
        return new self($resource, new MessageBag());
    }

    /** @return mixed */
    public function getResource()
    {
        return $this->resource;
    }

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }
}