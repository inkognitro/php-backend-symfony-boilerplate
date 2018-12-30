<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Response;

interface Response
{
    public function getStatusCode(): int;
}