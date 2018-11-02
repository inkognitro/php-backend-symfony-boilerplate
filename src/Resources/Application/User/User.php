<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Resources\Application\Resource;

final class User implements Resource
{
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
    }

    public static function createFromArray(array $array): self
    {
        $persistedRow = null;
        return new self(
            $array['id'],
            $array['username'],
            $array['emailAddress'],
            $persistedRow
        );
    }

    public function change(array $data): void
    {
        if (isset($data['emailAddress'])) {
            $this->emailAddress = $data['emailAddress'];
        }
        if (isset($data['username'])) {
            $this->username = $data['username'];
        }
    }

    public function isEqual(self $user): bool
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

    public function toCommandData(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'emailAddress' => $this->emailAddress
        ];
    }
}