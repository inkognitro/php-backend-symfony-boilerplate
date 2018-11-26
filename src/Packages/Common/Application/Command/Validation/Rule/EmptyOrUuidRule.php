<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command\Validation\Rule;

use App\Packages\Common\Application\Command\Validation\Message;
use App\Packages\Common\Application\Command\Validation\Message\MustBeAStringMessage;
use App\Packages\Common\Application\Command\Validation\Message\MustBeAUuidMessage;
use App\Packages\Common\Application\Command\Validation\Rule;
use Ramsey\Uuid\Uuid;

final class EmptyOrUuidRule implements Rule
{
    public function getMessageFromValidation($data): ?Message
    {
        if(!is_string($data)) {
            return new MustBeAStringMessage();
        }
        if(strlen($data) === 0) {
            return null;
        }
        if(!Uuid::isValid((string)$data)) {
            return new MustBeAUuidMessage();
        }
        return null;
    }
}