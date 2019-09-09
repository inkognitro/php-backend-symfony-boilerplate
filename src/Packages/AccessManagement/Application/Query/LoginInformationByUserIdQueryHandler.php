<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query;

interface LoginInformationByUserIdQueryHandler
{
    public function handle(LoginInformationByUserIdQuery $query): ?LoginInformation;
}