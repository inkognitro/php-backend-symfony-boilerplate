<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

interface JsonResponse extends Response
{
    public function toJson(): string;
}