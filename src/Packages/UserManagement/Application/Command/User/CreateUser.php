<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\User;

use App\Packages\Common\Application\Command\Command;
use App\Packages\UserManagement\Domain\Handlers\CreateUserHandler;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;

final class CreateUser implements Command
{
    public const USER_PARAMS = 'userParams';
    public const USER_MUST_BE_VERIFIED = 'userMustBeVerified';
    public const CREATOR = 'creator';

    private $userParams;
    private $userMustBeVerified;
    private $creator;

    private function __construct(UserParams $userParams, bool $userMustBeVerified, AuthUser $creator)
    {
        $this->userParams = $userParams;
        $this->userMustBeVerified = $userMustBeVerified;
        $this->creator = $creator;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data[self::USER_PARAMS],
            $data[self::USER_MUST_BE_VERIFIED],
            $data[self::CREATOR]
        );
    }

    public function getUserParams(): UserParams
    {
        return $this->userParams;
    }

    public function userMustBeVerified(): bool
    {
        return $this->userMustBeVerified;
    }

    public static function getCommandHandlerClass(): string
    {
        return CreateUserHandler::class;
    }

    public function getCommandExecutor(): AuthUser
    {
        return $this->creator;
    }
}