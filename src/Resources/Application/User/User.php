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
        $savedRow = null;
        return new self(
            $array['id'],
            $array['username'],
            $array['emailAddress'],
            $savedRow
        );
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
}