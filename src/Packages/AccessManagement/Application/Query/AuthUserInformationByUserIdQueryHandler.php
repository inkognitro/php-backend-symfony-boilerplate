<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query;

interface AuthUserInformationByUserIdQueryHandler
{
    public function handle(AuthUserInformationByUserIdQuery $query): ?AuthUserInformation;
}