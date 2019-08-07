<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\HandlerResponse;

use App\Packages\Common\Utilities\Validation\Messages\MessageBag;

final class ResourceCreatedResponse implements Success
{
    private $warnings;

    public function __construct(MessageBag $warnings)
    {
        $this->warnings = $warnings;
    }

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }
}