<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation;

use App\Packages\Common\Application\Validation\Messages\Message;

final class Validator
{
    public function getMessageFromValidation(array $data, Rules $rules): ?Message
    {
        return null; //todo
    }
}