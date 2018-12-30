<?php declare(strict_types=1);

namespace App\Api\WebApiV1Bundle;

use App\Api\WebApiV1Bundle\Endpoint\Endpoint;
use App\Api\WebApiV1Bundle\Endpoint\Endpoints;
use App\Api\WebApiV1Bundle\Endpoint\EndpointSchema;
use App\Api\WebApiV1Bundle\Endpoint\User\CreateUserEndpoint;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader
{
    private const TYPE = 'webApiV1Routes';
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

        if(count($this->endpoints->toCollection()) === 0) {
            die('FUCK SYMFONY!!!!!!!');
        }

        die('FUCK SYMFONY ANYWAY!!!!!!!');

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
        $endpointSchema = $endpoint->getSchema();
        $endpointClassName = get_class($endpoint);

        die('endpoint className = ' . $endpointClassName);

        $url = $endpointSchema->getUrlPart();
        $defaults = [
            //'_controller' => 'api_v1.endpoints.users.create_user::handle',
            '_controller' => [$endpointClassName, 'handle'],
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