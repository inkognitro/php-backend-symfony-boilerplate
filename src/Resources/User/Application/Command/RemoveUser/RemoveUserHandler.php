<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command\RemoveUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\Command\Validation\MessageBag;
use App\Packages\Common\Application\HandlerResponse\HandlerResponse;
use App\Packages\Common\Application\HandlerResponse\NotFoundResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Resources\Common\Application\HandlerResponse\RemovalSuccessResponse;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\Domain\Policy\RemoveUserPolicy;
use App\Resources\User\Application\Domain\UserRepository;

final class RemoveUserHandler
{
    private $userRepository;
    private $removeUserPolicy;

    public function __construct(UserRepository $userRepository, RemoveUserPolicy $userCommandPolicy)
    {
        $this->userRepository = $userRepository;
        $this->removeUserPolicy = $userCommandPolicy;
    }

    public function handle(RemoveUser $command, AuthUser $authUser): HandlerResponse
    {
        $user = $this->userRepository->findById(UserId::fromString($command->getUserId()));
        if ($user === null) {
            return new NotFoundResponse();
        }

        if (!$this->removeUserPolicy->isAuthorized($authUser, $user)) {
            return new UnauthorizedResponse();
        }

        $this->userRepository->remove($user, $authUser);
        return new RemovalSuccessResponse($user, new MessageBag());
    }
}