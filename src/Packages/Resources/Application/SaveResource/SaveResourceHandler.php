<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveResources;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\SuccessResponse;

final class SaveResourceHandler
{
    private $pep;

    public function __construct(SaveResourcePep $pep)
    {
        $this->pep = $pep;
    }

    public function handle(SaveResource $command, AuthUser $authUser): HandlerResponse
    {
        return new SuccessResponse(); //todo
    }
}