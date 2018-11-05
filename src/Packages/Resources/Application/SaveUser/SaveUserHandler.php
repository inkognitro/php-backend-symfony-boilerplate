<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\SuccessResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\ValidationErrorResponse;
use App\Resources\Application\User\User;
use App\Resources\Application\User\UserRepository;
use App\Resources\Application\User\UserDataValidator;

final class SaveUserHandler
{
    private $validator;
    private $userRepository;

    public function __construct(UserDataValidator $validator, UserRepository $userRepository)
    {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function handle(SaveUser $command, AuthUser $authUser): HandlerResponse
    {
        $user = $this->getCurrentUserByData($command->getUserData());
        if($user === null) {
            $user = User::createFromArray([]);
        }

        $userData = array_merge($user->toArray(), $command->getUserData());

        $this->validator->validate($userData);
        if($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }

        return new SuccessResponse([], $this->validator->getWarnings());
    }

    private function getCurrentUserByData(array $userData): ?User
    {
        if(!isset($userData['id'])) {
            return null;
        }
        return $this->userRepository->findById($userData['id']);
    }
}