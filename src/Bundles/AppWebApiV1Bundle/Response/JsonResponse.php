<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Response;

interface JsonResponse extends Response
{
    public function toJson(): string;
}