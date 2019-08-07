<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\User;

use App\Packages\Common\Application\Command\Command;
use App\Packages\UserManagement\Domain\Handlers\CreateUserHandler;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;

final class CreateUser implements Command
{
    public const USER_PARAMS = 'userParams';
    public const SEND_INVITATION = 'sendInvitation';
    public const CREATOR = 'creator';

    private $userParams;
    private $sendInvitation;
    private $creator;

    private function __construct(UserParams $userParams, bool $sendInvitation, AuthUser $creator)
    {
        $this->userParams = $userParams;
        $this->sendInvitation = $sendInvitation;
        $this->creator = $creator;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data[self::USER_PARAMS],
            ($data[self::SEND_INVITATION] ?? false),
            $data[self::CREATOR]
        );
    }

    public function getUserParams(): UserParams
    {
        return $this->userParams;
    }

    public function sendInvitation(): bool
    {
        return $this->sendInvitation;
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