<?php declare(strict_types=1);

namespace App\Packages\Resources;

use App\Packages\Resources\Property\Properties;

interface Resource
{
    public function getProperties(): Properties;
}