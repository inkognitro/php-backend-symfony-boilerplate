<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveResource;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\SuccessResponse;
use App\Packages\Resources\Application\PolicyEnforcementPoint;

final class SaveResourceHandler
{
    private $pep;

    public function __construct(PolicyEnforcementPoint $pep)
    {
        $this->pep = $pep;
    }

    public function handle(SaveResource $command, AuthUser $authUser): HandlerResponse
    {
        if($this->pep->isUserAuthorizedToWriteProperties($command, $authUser)) {
            
        }
        return new SuccessResponse();
    }
}