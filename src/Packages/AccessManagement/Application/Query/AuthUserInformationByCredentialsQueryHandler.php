<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query;

interface AuthUserInformationByCredentialsQueryHandler
{
    public function handle(AuthUserInformationByCredentialsQuery $query): ?AuthUserInformation;
}