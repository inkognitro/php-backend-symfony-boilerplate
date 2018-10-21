<?php declare(strict_types=1);

namespace App\Packages\Resources;

use \App\Packages\Authentication\Application\User;
use App\Packages\Resources\Property\Properties;

interface Resource
{
    public function getProperties(): Properties;
    public function getReadablePropertiesByUser(User $user): Properties;
    public function getWritablePropertiesByUser(User $user): Properties;
}