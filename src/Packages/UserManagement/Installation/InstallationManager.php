<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Installation;

use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserFactory;
use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\Common\Installation\Fixtures\Fixtures;
use App\Packages\Common\Installation\Migrations\Migrations;
use App\Packages\UserManagement\Installation\Fixtures\Dev\DevUserFixture;
use App\Packages\UserManagement\Installation\Fixtures\Prod\ProdUserFixture;
use App\Packages\UserManagement\Installation\Migrations\V1\UserEmailAddressVerificationCodesMigrationV1;
use App\Packages\UserManagement\Installation\Migrations\V1\UsersMigrationV1;

final class InstallationManager
{
    public function findMigrations(): Migrations
    {
        return new Migrations([
            new UsersMigrationV1(),
            new UserEmailAddressVerificationCodesMigrationV1(),
        ]);
    }

    public function findProdFixtures(CommandBus $commandBus, AuthUserFactory $authUserFactory): Fixtures
    {
        return new Fixtures([
            new ProdUserFixture($commandBus, $authUserFactory),
        ]);
    }

    public function findDevFixtures(CommandBus $commandBus, AuthUserFactory $authUserFactory): Fixtures
    {
        return new Fixtures([
            new DevUserFixture($commandBus, $authUserFactory),
        ]);
    }
}