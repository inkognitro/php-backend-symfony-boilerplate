<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use App\Api\ApiV1Bundle\Endpoint\EndpointSchema;
use App\Api\ApiV1Bundle\Endpoint\User\CreateUserEndpoint;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutesLoader extends Loader
{
    private $isLoaded;
    private $endpointSchemas;

    public function __construct()
    {
        $this->isLoaded = false;
        $this->endpointSchemas = $this->getEndpointSchemas();
    }

    /** @return EndpointSchema[] */
    private function getEndpointSchemas(): array
    {
        return [
            CreateUserEndpoint::class => CreateUserEndpoint::getSchema(),
        ];
    }

    public function load($resource, $type = null)
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Class "' . self::class . '" loaded twice!');
        }

        $routes = new RouteCollection();

        foreach($this->getEndpointSchemas() as $endpointClassName => $endpointSchema) {
            $url = $endpointSchema->getUrlPart();
            $defaults = [
                '_controller' => $endpointClassName . '::handle',
            ];
            $requirements = [];
            $options = [];
            $host = '';
            $schemes = [];
            $methods = [$endpointSchema->getMethod()];

            $route = new Route($url, $defaults, $requirements, $options, $host, $schemes, $methods);
            $routes->add($endpointClassName, $route);
        }

        $this->isLoaded = true;
        return $routes;
    }

    public function supports($resource, $type = null)
    {
        die('RoutesLoader::supports(' . $resource . ')'); //todo: test it!
        return true;
    }
}