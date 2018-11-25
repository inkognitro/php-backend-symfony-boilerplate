<?php declare(strict_types=1);

namespace App\Resources\Common\Application\HandlerResponse;

use App\Packages\Common\Application\Command\Validation\MessageBag;
use App\Packages\Common\Application\HandlerResponse\Success;

final class CreationSuccessResponse implements Success
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