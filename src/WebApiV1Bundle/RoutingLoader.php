<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use App\WebApiV1Bundle\Endpoints\Endpoint;
use App\WebApiV1Bundle\Schema\ApiSchema;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

final class RoutingLoader extends Loader
{
    private const TYPE = 'web_api_v1_routes';
    private $isLoaded;
    private $apiSchema;

    public function __construct(ApiSchema $apiSchema)
    {
        $this->isLoaded = false;
        $this->apiSchema = $apiSchema;
    }

    public function load($resource, $type = null)
    {
        if ($this->isLoaded === true) {
            throw new \RuntimeException('Class "' . self::class . '" loaded twice');
        }
        $routes = new RouteCollection();
        $this->addDocumentationRoutes($routes);
        $this->addEndpointRoutes($routes);
        $this->isLoaded = true;
        return $routes;
    }

    private function addDocumentationRoutes(RouteCollection $routes): void
    {
        $requestMethod = 'GET';
        $url = '';
        $serviceClass = DocumentationController::class;
        $serviceClassMethod = 'show';
        $documentationIndexRoute = $this->createRoute($requestMethod, $url, $serviceClass, $serviceClassMethod);
        $routes->add(WebApiV1Bundle::class . 'Documentation', $documentationIndexRoute);
    }

    private function addEndpointRoutes(RouteCollection $routes): void
    {
        foreach ($this->apiSchema->getEndpoints()->toIterable() as $endpoint) {
            /** @var $endpoint Endpoint */
            $endpointClassName = get_class($endpoint);
            $routes->add($endpointClassName, $this->createEndpointRoute($endpoint));
        }
    }

    private function createEndpointRoute(Endpoint $endpoint): Route
    {
        $endpointSchema = $endpoint::getSchema();
        $requestMethod = $endpointSchema->getRequestMethod()->toString();
        $url = $endpointSchema->getPath();
        $endpointService = get_class($endpoint);
        $endpointServiceMethod = 'handle';
        return $this->createRoute($requestMethod, $url, $endpointService, $endpointServiceMethod);
    }

    private function createRoute(string $requestMethod, string $url, string $serviceClass, string $serviceMethod): Route
    {
        $defaults = [
            '_controller' => [$serviceClass, $serviceMethod]
        ];
        $requirements = [];
        $options = [];
        $host = '';
        $schemes = ['http', 'https'];
        $methods = [$requestMethod];
        return new Route($url, $defaults, $requirements, $options, $host, $schemes, $methods);
    }

    public function supports($resource, $type = null)
    {
        return ($type === self::TYPE);
    }
}