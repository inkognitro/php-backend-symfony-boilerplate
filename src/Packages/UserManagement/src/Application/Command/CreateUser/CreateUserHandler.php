<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\CreateUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceCreatedResponse;
use App\Packages\UserManagement\Application\Resources\User\UserRepository;
use App\Packages\UserManagement\Domain\User\UserValidator;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Domain\User\UserManager;

final class CreateUserHandler
{
    private $validator;
    private $userRepository;
    private $userFactory;
    private $userManager;

    public function __construct(
        UserValidator $validator,
        UserRepository $userRepository,
        UserFactory $userFactory,
        UserManager $userManager
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->userManager = $userManager;
    }

    public function handle(CreateUser $command, AuthUser $creator): Response
    {
        $user = $this->userRepository->findById(UserId::fromString($command->getUserId()));
        if ($user !== null) {
            return new UnauthorizedResponse();
        }

        $user = $this->userFactory->convert($command);

        $this->validator->validate($user);

        if ($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }

        $userManager = UserManager::create($user, $creator);
        $userManager->dispatch();

        return new ResourceCreatedResponse($user, $this->validator->getWarnings());
    }
}