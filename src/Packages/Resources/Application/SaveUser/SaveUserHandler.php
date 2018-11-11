<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\Event\EventStream;
use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\SuccessResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\ValidationErrorResponse;
use App\Resources\User\Application\Command\CommandUser;
use App\Resources\User\Application\Command\UserDataValidator;
use App\Resources\User\Application\Property\UserId;
use App\Resources\User\Application\UserRepository;

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
        $this->validator->validate();


        $user = $this->getCurrentUserByData($command->getUserData(), $authUser);
        if($user === null) {
            $user = CommandUser::create([]);
        }

        $userData = array_merge($user->toArray(), $command->getUserData());

        $this->validator->validate($userData);
        if($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }

        return new SuccessResponse(new EventStream([]), $this->validator->getWarnings());
    }

    private function getCurrentUserByData(array $userData, AuthUser $authUser): CommandUser
    {
        $user = null;
        if(isset($userData['id'])) {
            $user = $this->userRepository->findById(UserId::fromString($userData['id']));
        }
        if($user === null) {
            return CommandUser::create($userData, $authUser);
        }
        return CommandUser::fromUser($user);
    }
}