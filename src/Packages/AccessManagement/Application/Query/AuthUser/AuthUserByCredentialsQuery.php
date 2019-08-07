<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query\AuthUser;

final class AuthUserByCredentialsQuery
{
    private $username;
    private $password;

    private function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public static function fromCredentials(string $username, string $password): self
    {
        return new self($username, $password);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}