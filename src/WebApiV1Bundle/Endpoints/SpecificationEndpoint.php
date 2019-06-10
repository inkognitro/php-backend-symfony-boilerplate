<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints;

use App\WebApiV1Bundle\Response\HttpResponseFactory;
use App\WebApiV1Bundle\Response\JsonSuccessResponse;
use App\WebApiV1Bundle\Schema\ApiSchema;
use App\WebApiV1Bundle\Schema\EndpointSchema;
use App\WebApiV1Bundle\Schema\RequestMethod;
use App\WebApiV1Bundle\Schema\UrlFragments;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class SpecificationEndpoint implements Endpoint
{
    private $httpResponseFactory;
    private $apiSchema;

    public function __construct(HttpResponseFactory $httpResponseFactory, ApiSchema $apiSchema)
    {
        $this->httpResponseFactory = $httpResponseFactory;
        $this->apiSchema = $apiSchema;
    }

    public function handle(): HttpResponse
    {
        $request = Request::createFromGlobals();
        $apiResponse = JsonSuccessResponse::fromData($this->apiSchema->toOpenApiV2Array());
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
