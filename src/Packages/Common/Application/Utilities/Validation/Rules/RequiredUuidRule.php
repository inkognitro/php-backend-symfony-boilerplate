<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\Validation\Rules;

use App\Packages\Common\Application\Utilities\Validation\Messages\Message;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustBeAStringMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustBeAUuidMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustBeLowerCaseMessage;
use App\Packages\Common\Application\Utilities\Validation\Messages\MustNotBeEmptyMessage;
use Ramsey\Uuid\Uuid;

final class RequiredUuidRule implements Rule
{
    /** @param $uuid mixed */
    public static function findError($uuid): ?Message
    {
        if (!is_string($uuid)) {
            return new MustBeAStringMessage();
        }
        if (strlen($uuid) === 0) {
            return new MustNotBeEmptyMessage();
        }
        if (!Uuid::isValid($uuid)) {
            return new MustBeAUuidMessage();
        }
        if (preg_match('/[A-F]/', $uuid)) {
            return new MustBeLowerCaseMessage();
        }
        return null;
    }
}