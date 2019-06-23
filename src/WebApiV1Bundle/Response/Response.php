<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use App\WebApiV1Bundle\Schema\ResponseSchema;

interface Response
{
    public static function getHttpStatusCode(): int;
    public static function getSchema(): ResponseSchema;
}