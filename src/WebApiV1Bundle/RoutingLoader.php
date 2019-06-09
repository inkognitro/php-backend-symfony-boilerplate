<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use App\WebApiV1Bundle\Endpoints\Endpoint;
use App\WebApiV1Bundle\Schema\ApiSchema;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
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
            throw new \RuntimeException('Class "' . self::class . '" loaded twice!');
        }
        $routes = new RouteCollection();
        foreach($this->apiSchema->getEndpoints()->toIterable() as $endpoint) {
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
        $endpointSchema = $endpoint::getSchema();
        $url = $this->apiSchema->getDocumentationBasePath() . $endpointSchema->getPath();
        $defaults = [
            '_controller' => [$endpointServiceName, 'handle']
        ];
        $requirements = [];
        $options = [];
        $host = '';
        $schemes = [];
        $methods = [$endpointSchema->getRequestMethod()->toString()];
        return new Route($url, $defaults, $requirements, $options, $host, $schemes, $methods);
    }

    public function supports($resource, $type = null)
    {
        return ($type === self::TYPE);
    }
}