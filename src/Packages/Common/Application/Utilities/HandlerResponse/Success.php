<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\HandlerResponse;

use App\Packages\Common\Utilities\Validation\Messages\MessageBag;

interface Success extends Response
{
    public function getWarnings(): MessageBag;
}