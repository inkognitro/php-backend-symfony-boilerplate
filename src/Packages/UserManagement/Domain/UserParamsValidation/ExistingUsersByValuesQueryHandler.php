<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserParamsValidation;

interface ExistingUsersByValuesQueryHandler
{
    public function handle(ExistingUsersByValuesQuery $query): Users;
}
