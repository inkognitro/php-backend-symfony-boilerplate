<?php declare(strict_types=1);

namespace App\Resources\Application\User;

final class UserPermission
{
    private $id;
    private $username;
    private $emailAddress;

    private function __construct(string $id, string $username, string $emailAddress)
    {
        $this->id = $id;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
    }

    public static function createFromRow(array $row): self
    {
        return new self(
            $row['id'],
            $row['username'],
            $row['emailAddress']
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