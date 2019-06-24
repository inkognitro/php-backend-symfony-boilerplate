<?php declare(strict_types=1);

namespace App\Utilities\HandlerResponse;

use App\Utilities\Validation\Messages\MessageBag;

interface Success extends Response
{
    public function getWarnings(): MessageBag;
}