<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints\User;

use App\Utilities\HandlerResponse\SuccessResponse as SuccessHandlerResponse;
use App\WebApiV1Bundle\Endpoints\Endpoint;
use App\WebApiV1Bundle\Endpoints\Response\HttpResponseFactory;
use App\WebApiV1Bundle\Schema\EndpointSchema;
use App\WebApiV1Bundle\Schema\RequestMethod;
use App\WebApiV1Bundle\Schema\UrlFragment;
use App\WebApiV1Bundle\Schema\UrlFragments;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class CreateUserEndpoint implements Endpoint
{
    private $httpResponseFactory;

    public function __construct(HttpResponseFactory $httpResponseFactory)
    {
        $this->httpResponseFactory = $httpResponseFactory;
    }

    public function handle(): HttpResponse
    {
        $request = Request::createFromGlobals();
        $response = SuccessHandlerResponse::create();
        return $this->httpResponseFactory->createFromHandlerResponse($response, $request);
    }

    public static function getSchema(): EndpointSchema
    {
        $urlFragments = UrlFragments::create();
        $urlFragments = $urlFragments->add(UrlFragment::fromString('user'));
        $endpointSchema = EndpointSchema::create(RequestMethod::post(), $urlFragments);
        $endpointSchema = $endpointSchema->setSummary('Create a new user.');
        $endpointSchema = $endpointSchema->setTags(['UserManagement']);
        return $endpointSchema;
    }
}
