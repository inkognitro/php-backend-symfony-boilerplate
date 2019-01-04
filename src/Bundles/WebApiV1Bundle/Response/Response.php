<?php declare(strict_types=1);

namespace App\Bundles\WebApiV1Bundle\Response;

interface Response
{
    public function getStatusCode(): int;
}