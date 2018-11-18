<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command\RemoveUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\NotFoundResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\SuccessResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\UnauthorizedResponse;
use App\Resources\User\Application\Command\UserCommandPolicy;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\UserRepository;

final class RemoveUserHandler
{
    private $userRepository;
    private $userCommandPolicy;

    public function __construct(UserRepository $userRepository, UserCommandPolicy $userCommandPolicy)
    {
        $this->userRepository = $userRepository;
        $this->userCommandPolicy = $userCommandPolicy;
    }

    public function handle(RemoveUser $command, AuthUser $authUser): HandlerResponse
    {
        $user = $this->userRepository->findById(UserId::fromString($command->getUserId()));
        if ($user === null) {
            return new NotFoundResponse();
        }

        if (!$this->userCommandPolicy->remove($authUser, $user)) {
            return new UnauthorizedResponse();
        }

        $this->userRepository->remove($user, $authUser);
        return SuccessResponse::fromData($user->toArray());
    }
}