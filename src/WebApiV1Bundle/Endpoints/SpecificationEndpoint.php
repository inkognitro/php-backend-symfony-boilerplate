<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints;

use App\WebApiV1Bundle\Response\HttpResponseFactory;
use App\WebApiV1Bundle\Response\JsonPlainDataSuccessResponse;
use App\WebApiV1Bundle\Schema\ApiSchema;
use App\WebApiV1Bundle\Schema\EndpointSchema;
use App\WebApiV1Bundle\Schema\RequestMethod;
use App\WebApiV1Bundle\Schema\UrlFragments;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class SpecificationEndpoint implements Endpoint
{
    private const OPEN_API_V2_SCHEMA_CACHE_KEY = 'openApi2Schema6341b682';
    private $httpResponseFactory;
    private $apiSchema;
    private $cache;

    public function __construct(HttpResponseFactory $httpResponseFactory, ApiSchema $apiSchema, CacheInterface $cache)
    {
        $this->httpResponseFactory = $httpResponseFactory;
        $this->apiSchema = $apiSchema;
        $this->cache = $cache;
    }

    public function handle(): HttpResponse
    {
        $request = Request::createFromGlobals();
        $schemaData = $this->cache->get(self::OPEN_API_V2_SCHEMA_CACHE_KEY, function (ItemInterface $item) {
            return $this->apiSchema->toOpenApiV2Array();
        });
        $apiResponse = JsonPlainDataSuccessResponse::fromData($schemaData);
        return $this->httpResponseFactory->create($apiResponse, $request);
    }

    public static function getSchema(): EndpointSchema
    {
        $urlFragments = UrlFragments::fromStrings(['specification']);
        $endpointSchema = EndpointSchema::create(RequestMethod::get(), $urlFragments);
        $endpointSchema = $endpointSchema->setSummary('The open api version 2 specification.');
        $endpointSchema = $endpointSchema->setTags(['Specification']);
        return $endpointSchema;
    }
}
