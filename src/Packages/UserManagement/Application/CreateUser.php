<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Commands\CreateUser;

use App\Packages\Common\Application\Command\Command;
use App\Resources\User\Attributes\UserId;

final class CreateUser implements Command
{
    public const ID = UserId::getKey();
    public const USERNAME = Username::KEY;
    public const PASSWORD = Password::KEY;
    public const EMAIL_ADDRESS = EmailAddress::KEY;
    public const ROLE = RoleId::KEY;
    public const SEND_INVITATION = 'sendInvitation';

    private $userId;
    private $username;
    private $emailAddress;
    private $password;
    private $role;
    private $sendInvitation;

    public static function getHandlerClass(): string
    {
        return CreateUserHandler::class;
    }

    private function __construct(
        string $userId,
        string $username,
        string $emailAddress,
        string $password,
        ?string $role,
        bool $sendInvitation
    )
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->role = $role;
        $this->sendInvitation = $sendInvitation;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data[self::ID],
            $data[self::USERNAME],
            $data[self::EMAIL_ADDRESS],
            $data[self::PASSWORD],
            ($data[self::ROLE] ?? null),
            $data[self::SEND_INVITATION]
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function sendInvitation(): bool
    {
        return $this->sendInvitation;
    }
}