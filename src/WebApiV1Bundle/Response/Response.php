<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

interface Response
{
    public function getHttpStatusCode(): int;
}