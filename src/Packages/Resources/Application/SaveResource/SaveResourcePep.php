<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveResources;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Resources\Application\Policy;
use App\Resources\Application\Resource;
use Symfony\Component\DependencyInjection\Container;

final class SaveResourcePep
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function isAuthorizedToEdit(AuthUser $authUser, Resource $resource, array $attributeNames): bool
    {
        $policyClassName = get_class($resource) . 'Policy';
        /** @var $policy Policy */
        $policy = $this->container->get($policyClassName);
        return $policy->canEdit($authUser, $resource, $attributeNames);
    }
}