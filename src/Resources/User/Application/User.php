<?php declare(strict_types=1);

namespace App\Resources\User\Application;

use App\Resources\Common\Application\Resource;
use App\Resources\User\Application\Attribute\EmailAddress;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\Attribute\Username;

final class User implements Resource
{
    private $id;
    private $username;
    private $emailAddress;

    protected function __construct(string $id, string $username, string $emailAddress)
    {
        $this->id = $id;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
    }

    public function isEqualTo(self $user): bool
    {
        if (!$this->getId()->isEqualTo($user->getId())) {
            return false;
        }
        if (!$this->getUsername()->isEqualTo($user->getUsername())) {
            return false;
        }
        if (!$this->getEmailAddress()->isEqualTo($user->getEmailAddress())) {
            return false;
        }
        return true;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array[UserId::NAME],
            $array[Username::NAME],
            $array[EmailAddress::NAME]
        );
    }

    public function getId(): UserId
    {
        return UserId::fromString($this->id);
    }

    public function getUsername(): Username
    {
        return Username::fromString($this->username);
    }

    public function getEmailAddress(): EmailAddress
    {
        return EmailAddress::fromString($this->emailAddress);
    }

    public function toArray(): array
    {
        return [
            UserId::NAME => $this->getId()->toString(),
            Username::NAME => $this->getUsername()->toString(),
            EmailAddress::NAME => $this->getEmailAddress()->toString()
        ];
    }
}