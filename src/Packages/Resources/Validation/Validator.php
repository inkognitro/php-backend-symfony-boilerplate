<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation;

interface Validator
{
    public function validate(Resource $resource): void;
    public function getErrorMessages(): array;
}