<?php declare(strict_types=1);

namespace App\Api\WebApiV1;

use App\Api\WebApiV1\Endpoint\Endpoints;
use App\Api\WebApiV1\Endpoint\EndpointSchema;
use App\Api\WebApiV1\Endpoint\User\CreateUserEndpoint;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
{
    private $isLoaded;
    private $endpointSchemas;
    private $endpoints;

    public function __construct(Endpoints $endpoints)
    {
        $this->isLoaded = false;
        $this->endpointSchemas = $this->getEndpointSchemas();
        $this->endpoints = $endpoints;
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
        if ($this->isLoaded === true) {
            throw new \RuntimeException('Class "' . self::class . '" loaded twice!');
        }

        $routes = new RouteCollection();
        foreach($this->getEndpointSchemas() as $endpointClassName => $endpointSchema) {
            $routes->add($endpointClassName, $this->createRoute($endpointClassName, $endpointSchema));
        }

        $this->isLoaded = true;
        return $routes;
    }

    private function createRoute(string $endpointClassName, EndpointSchema $endpointSchema): Route
    {
        $url = $endpointSchema->getUrlPart();
        $defaults = [
            '_controller' => 'api_v1.endpoints.users.create_user::handle',
            //'_controller' => [$endpointClassName, 'handle'],
        ];
        $requirements = [];
        $options = [];
        $host = '';
        $schemes = [];
        $methods = [$endpointSchema->getMethod()];
        return new Route($url, $defaults, $requirements, $options, $host, $schemes, $methods);
    }

    public function supports($resource, $type = null)
    {
        return ($type === 'WebApiV1Routes');
    }
}