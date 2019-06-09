<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

use App\WebApiV1Bundle\Endpoints\Endpoint;
use App\WebApiV1Bundle\Endpoints\Endpoints;

final class ApiSchema
{
    private $endpoints;
    private $openApiV2Data;

    public function __construct(Endpoints $endpoints)
    {
        $this->endpoints = $endpoints;
        $this->openApiV2Data = [
            'swagger' => '2.0',
            'info' => [
                'description' => 'You can find out more about this project at [github.com/inkognitro](http://github.com/inkognitro/symfonyApi).',
                'version' => '1.0.0',
                'title' => 'Boilerplate Api',
            ],
            'host' => getenv('APP_API_HOST'),
            'basePath' => '/v1/documentation',
            'securityDefinitions' => [
                'ApiKeyAuthentication' => [
                    'type' => 'apiKey',
                    'name' => 'X-API-KEY',
                    'in' => 'header',
                ],
            ],
        ];
    }

    public function getEndpoints(): Endpoints
    {
        return $this->endpoints;
    }

    public function getDocumentationBasePath(): string
    {
        return $this->openApiV2Data['host'] . $this->openApiV2Data['basePath'];
    }

    private function getOpenApiV2PathFragments(): array
    {
        $paths = [];
        foreach($this->endpoints->toIterable() as $endpoint) {
            /** @var $endpoint Endpoint */
            $endpointSchema = $endpoint::getSchema();
            $fragment = $endpointSchema->toOpenApiPathMethodFragment();
            $paths[$endpointSchema->getOpenApiPath()][$endpointSchema->getOpenApiMethod()] = $fragment;
        }
        return $paths;
    }

    public function toOpenApiV2Array(): array
    {
        return array_merge($this->openApiV2Data, [
            'paths' => $this->getOpenApiV2PathFragments()
        ]);
    }
}