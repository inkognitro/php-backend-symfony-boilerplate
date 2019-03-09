<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User;

use App\Packages\UserManagement\Domain\User\Attributes\Values\EmailAddress;
use App\Packages\UserManagement\Domain\User\Attributes\Values\UserId;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Username;

interface UserQuery
{
    public function findById(UserId $userId): ?User;
    public function findByUsername(Username $username): ?User;
    public function findByEmailAddress(EmailAddress $emailAddress): ?User;
}