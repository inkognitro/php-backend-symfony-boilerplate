<?php declare(strict_types=1);

namespace App\Resources\User\Application;

use App\Resources\Common\Application\Resource;
use App\Resources\User\Application\Property\EmailAddress;
use App\Resources\User\Application\Property\UserId;
use App\Resources\User\Application\Property\Username;

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

    public function isEqual(self $user): bool
    {
        if (!$this->getId()->equals($user->getId())) {
            return false;
        }
        if (!$this->getUsername()->equals($user->getUsername())) {
            return false;
        }
        if (!$this->getEmailAddress()->equals($user->getEmailAddress())) {
            return false;
        }
        return true;
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'],
            $array['username'],
            $array['emailAddress']
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
            'id' => $this->getId()->toString(),
            'username' => $this->getUsername()->toString(),
            'emailAddress' => $this->getEmailAddress()->toString()
        ];
    }
}