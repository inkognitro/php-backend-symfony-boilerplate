<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\SuccessResponse;

final class SaveUserHandler
{
    public function handle(SaveUser $command, AuthUser $authUser): HandlerResponse
    {
        return new SuccessResponse(); //todo
    }
}