<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command\Validation\Rule;

use App\Packages\Common\Application\Command\Validation\Message;
use App\Packages\Common\Application\Command\Validation\Messages\MustNotBeEmptyMessage;
use App\Packages\Common\Application\Command\Validation\Rule;

final class NotEmptyRule implements Rule
{
    public function getMessageFromValidation($data): ?Message
    {
        $data = (string)$data;
        if(strlen(trim($data)) === 0) {
            return new MustNotBeEmptyMessage();
        }
        return null;
    }
}