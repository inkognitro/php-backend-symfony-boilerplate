<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\HandlerResponse;

use App\Packages\Common\Application\Query\Resource;

final class ResourceCreatedResponse implements Success
{
    private $resource;

    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }
}