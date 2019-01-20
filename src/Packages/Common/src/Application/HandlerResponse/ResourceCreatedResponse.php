<?php declare(strict_types=1);

namespace App\Packages\Common\Application\HandlerResponse;

use App\Packages\Common\Application\Resources\AbstractResource;
use App\Packages\Common\Application\Validation\Messages\MessageBag;

final class ResourceCreatedResponse implements Success
{
    private $resource;
    private $warnings;

    public function __construct(AbstractResource $resource, MessageBag $warnings)
    {
        $this->resource = $resource;
        $this->warnings = $warnings;
    }

    public function getResource(): AbstractResource
    {
        return $this->resource;
    }

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }
}