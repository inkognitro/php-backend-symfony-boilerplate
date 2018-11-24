<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command\Validation;

use App\Packages\Common\Application\Command\Validation\Message;

interface Rule
{
    /** @param $data mixed */
    public function getMessageFromValidation($data): ?Message;
}