<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\RemoveUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\Validation\Messages\MessageBag;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\NotFoundResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceRemovedResponse;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Domain\User\Policy\RemoveUserPolicy;
use App\Packages\UserManagement\Domain\User\UserRepository;

final class RemoveUserHandler
{
    private $userRepository;
    private $removeUserPolicy;

    public function __construct(UserRepository $userRepository, RemoveUserPolicy $userCommandPolicy)
    {
        $this->userRepository = $userRepository;
        $this->removeUserPolicy = $userCommandPolicy;
    }

    public function handle(RemoveUser $command, AuthUser $authUser): Response
    {
        $user = $this->userRepository->findById(UserId::fromString($command->getUserId()));
        if ($user === null) {
            return new NotFoundResponse();
        }

        if (!$this->removeUserPolicy->isAuthorized($authUser, $user)) {
            return new UnauthorizedResponse();
        }

        $this->userRepository->remove($user, $authUser);
        return new ResourceRemovedResponse($user, new MessageBag());
    }
}