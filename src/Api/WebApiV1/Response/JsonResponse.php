<?php declare(strict_types=1);

namespace App\Api\WebApiV1\Response;

interface JsonResponse extends Response
{
    public function toJson(): string;
}