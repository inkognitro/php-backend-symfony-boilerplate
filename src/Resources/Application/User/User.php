<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\Event\Payload;
use App\Resources\Application\Application\User\Event\UserWasChanged;
use App\Resources\Application\Resource;
use Ramsey\Uuid\Uuid;

final class User implements Resource
{
    private $futureEvents;
    private $persistedRow;
    private $id;
    private $username;
    private $emailAddress;

    private function __construct(string $id, string $username, string $emailAddress, ?array $persistedRow)
    {
        $this->id = $id;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->persistedRow = $persistedRow;
        $this->futureEvents = [];
    }

    public static function createFromArray(array $array): self
    {
        $persistedRow = null;
        $id = (isset($array['id']) ? $array['id'] : Uuid::uuid4()->toString());
        return new self(
            $id,
            ($array['username'] ?? ''),
            ($array['emailAddress'] ?? ''),
            $persistedRow
        );
    }

    public function change(array $data, AuthUser $authUser): void
    {
        if (count($data) === 0) {
            return;
        }

        $changedData = array_merge($this->toArray(), $data);
        if (!$this->isEqual(self::createFromArray($changedData))) {
            return;
        }

        $previousPayload = Payload::fromData($this->toArray());

        if (isset($data['emailAddress'])) {
            $this->emailAddress = $data['emailAddress'];
        }

        if (isset($data['username'])) {
            $this->username = $data['username'];
        }

        $payload = Payload::fromData($this->toArray());
        
        $this->futureEvents[] = UserWasChanged::occur($payload, $previousPayload, $authUser);
    }

    private function isEqual(self $user): bool
    {
        if (strcasecmp($this->id, $user->id) !== 0) {
            return false;
        }
        if ($this->username !== $user->username) {
            return false;
        }
        if ($this->emailAddress !== $user->emailAddress) {
            return false;
        }
        return true;
    }

    public function getLastPersisted(): ?self
    {
        if ($this->persistedRow === null) {
            return null;
        }
        return self::createFromRow($this->persistedRow);
    }

    public static function createFromRow(array $row): self
    {
        return new self(
            $row['id'],
            $row['username'],
            $row['emailAddress'],
            $row
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'emailAddress' => $this->emailAddress
        ];
    }
}