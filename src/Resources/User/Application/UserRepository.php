<?php declare(strict_types=1);

namespace App\Resources\User\Application;

use App\Resources\User\Application\Command\CommandUser as CommandUser;
use App\Resources\User\Application\Property\EmailAddress;
use App\Resources\User\Application\Property\UserId;
use App\Resources\User\Application\Property\Username;

interface UserRepository
{
    public function findById(UserId $id): ?User;

    public function findByUsername(Username $username): ?User;

    public function findByEmailAddress(EmailAddress $emailAddress): ?User;

    public function save(CommandUser $user): void;
}