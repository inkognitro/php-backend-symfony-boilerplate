<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\RemoveUser;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\ResourceNotFoundResponse;
use App\Packages\Common\Application\Validation\Messages\MessageBag;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceRemovedResponse;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Application\Resources\User\UserRepository;
use App\Packages\UserManagement\Domain\User\Policies\RemoveUserPolicy;

final class RemoveUserHandler
{
    private $userRepository;
    private $removeUserPolicy;

    public function __construct(RemoveUserPolicy $removeUserPolicy, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->removeUserPolicy = $removeUserPolicy;
    }

    public function handle(RemoveUser $command, AuthUser $authUser): Response
    {
        $user = null; //todo: find user from repository

        if ($user === null) {
            return new ResourceNotFoundResponse();
        }

        if (!$this->removeUserPolicy->isAuthorized($authUser, $user)) {
            return new UnauthorizedResponse();
        }

        //$this->userRepository->remove($user, $authUser);

        return new ResourceRemovedResponse($user, new MessageBag());
    }
}