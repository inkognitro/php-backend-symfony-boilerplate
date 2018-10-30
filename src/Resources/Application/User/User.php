<?php declare(strict_types=1);

namespace App\Resources\Application\User;

final class User
{
    private $savedRow;
    private $id;
    private $username;
    private $emailAddress;

    private function __construct(string $id, string $username, string $emailAddress, ?$savedRow)
    {
        $this->id = $id;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->savedRow = $savedRow;
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