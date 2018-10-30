<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation;

use App\Packages\Common\Application\Validation\Messages\Message;

interface Rule
{
    /** @param $data mixed */
    public function getValidationError($data): ?Message;
}