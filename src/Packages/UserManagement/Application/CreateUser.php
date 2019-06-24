<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application;

use App\Packages\Common\Application\Command;
use App\Packages\UserManagement\Domain\Handlers\CreateUserHandler;
use App\Utilities\Authentication\AuthUser;

final class CreateUser implements Command
{
    public const USER_ID = 'id';
    public const USERNAME = 'username';
    public const PASSWORD = 'password';
    public const EMAIL_ADDRESS = 'emailAddress';
    public const ROLE_ID = 'roleId';
    public const SEND_INVITATION = 'sendInvitation';
    public const CREATOR = 'creator';

    private $userId;
    private $username;
    private $emailAddress;
    private $password;
    private $roleId;
    private $sendInvitation;
    private $creator;

    public static function getHandlerClass(): string
    {
        return CreateUserHandler::class;
    }

    private function __construct(
        string $userId,
        string $username,
        string $emailAddress,
        string $password,
        ?string $roleId,
        bool $sendInvitation,
        AuthUser $creator
    )
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->roleId = $roleId;
        $this->sendInvitation = $sendInvitation;
        $this->creator = $creator;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data[self::USER_ID],
            $data[self::USERNAME],
            $data[self::EMAIL_ADDRESS],
            $data[self::PASSWORD],
            ($data[self::ROLE_ID] ?? null),
            $data[self::SEND_INVITATION],
            $data[self::CREATOR]
        );
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoleId(): ?string
    {
        return $this->roleId;
    }

    public function sendInvitation(): bool
    {
        return $this->sendInvitation;
    }

    public function getExecutor(): AuthUser
    {
        return $this->creator;
    }
}