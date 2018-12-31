<?php declare(strict_types=1);

namespace App\Packages\Common\Application\HandlerResponse;

use App\Packages\Common\Application\Validation\Messages\MessageBag;
use App\Packages\Common\Application\HandlerResponse\SuccessResponse;

final class ResourceRemovedResponse implements SuccessResponse
{
    private $resource;
    private $warnings;

    /** @param $resource mixed */
    public function __construct($resource, MessageBag $warnings)
    {
        $this->resource = $resource;
        $this->warnings = $warnings;
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