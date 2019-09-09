<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query;

interface LoginInformationByUserCredentialsQueryHandler
{
    public function handle(LoginInformationByUserCredentialsQuery $query): ?LoginInformation;
}