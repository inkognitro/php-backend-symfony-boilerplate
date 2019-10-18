<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Installation;

use App\Packages\Common\Installation\Migrations\Migrations;
use App\Packages\JobQueueManagement\Installation\Migrations\V1\JobsMigrationV1;

final class InstallationManager
{
    public function findMigrations(): Migrations
    {
        return new Migrations([
            new JobsMigrationV1()
        ]);
    }
}