<?php declare(strict_types=1);

namespace App\Packages\Common\Installation;

use App\Packages\Common\Installation\Fixtures\Fixtures;
use App\Packages\Common\Installation\Migrations\AuditLogMigrationV1;
use App\Packages\Common\Installation\Migrations\Migrations;
use App\Packages\Common\Installation\Migrations\V1\MigrationsMigrationV1;

final class InstallationManager
{
    public function findMigrations(): Migrations
    {
        return new Migrations([
            new MigrationsMigrationV1(),
            new AuditLogMigrationV1(),
        ]);
    }

    public function findProdFixtures(): Fixtures
    {
        return new Fixtures([]);
    }
}