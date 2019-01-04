<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\CreateUser;

use App\Packages\Common\Application\Command\Command;

final class CreateUser implements Command
{
    private $userId;
    private $username;
    private $emailAddress;
    private $password;
    private $role;
    private $sendInvitation;

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

    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'],
            $array['username'],
            $array['emailAddress'],
            $array['password'],
            ($array['role'] ?? null),
            $array['sendInvitation']
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