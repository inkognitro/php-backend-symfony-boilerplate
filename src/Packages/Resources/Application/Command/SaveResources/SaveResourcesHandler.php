<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\Command\SaveResources;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\SuccessResponse;

final class SaveResourcesHandler
{
    private $validator;

    public function __construct(SaveResourcesValidator $validator)
    {
        $this->validator = $validator;
    }

    public function handle(SaveResources $command, AuthUser $authUser): HandlerResponse
    {
        if($this->pep->isUserAuthorizedToWriteProperties($command, $authUser)) {

        }
        return new SuccessResponse();
    }
}