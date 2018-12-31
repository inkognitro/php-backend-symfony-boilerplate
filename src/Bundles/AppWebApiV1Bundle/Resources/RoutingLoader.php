<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Resources;

use AppWebApiV1Bundle\Endpoint\Endpoint;
use AppWebApiV1Bundle\Endpoint\Endpoints;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
{
    private const TYPE = 'app_web_api_v1_routes';
    private $isLoaded;
    private $endpoints;

    public function __construct(Endpoints $endpoints)
    {
        $this->isLoaded = false;
        $this->endpoints = $endpoints;
    }

    public function load($resource, $type = null)
    {
        if ($this->isLoaded === true) {
            throw new \RuntimeException('Class "' . self::class . '" loaded twice!');
        }

        $routes = new RouteCollection();
        foreach($this->endpoints->toCollection() as $endpoint) {
            /** @var $endpoint Endpoint */
            $endpointClassName = get_class($endpoint);
            $routes->add($endpointClassName, $this->createRoute($endpoint));
        }

        $this->isLoaded = true;
        return $routes;
    }

    private function createRoute(Endpoint $endpoint): Route
    {
        $endpointServiceName = get_class($endpoint);
        $endpointSchema = $endpoint->getSchema();
        $url = $endpointSchema->getUrlPart();
        $defaults = [
            '_controller' => [$endpointServiceName, 'handle']
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
        return ($type === self::TYPE);
    }
}