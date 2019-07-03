<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints\Auth;

use App\Packages\Common\Application\Authentication\UserQuery;
use App\Packages\Common\Application\Authentication\UserQueryHandler;
use App\WebApiV1Bundle\Authentication\JWTFactory;
use App\WebApiV1Bundle\Endpoints\Endpoint;
use App\WebApiV1Bundle\Response\HttpResponseFactory;
use App\WebApiV1Bundle\Response\JsonBadRequestResponse;
use App\WebApiV1Bundle\Response\JsonSuccessResponse;
use App\WebApiV1Bundle\Response\JsonUnauthorizedResponse;
use App\WebApiV1Bundle\Schema\EndpointSchema;
use App\WebApiV1Bundle\Schema\RequestMethod;
use App\WebApiV1Bundle\Schema\RequestParameterSchema;
use App\WebApiV1Bundle\Schema\UrlFragments;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class AuthenticateEndpoint implements Endpoint
{
    private $httpResponseFactory;
    private $userQueryHandler;
    private $JWTFactory;

    public function __construct(
        HttpResponseFactory $httpResponseFactory,
        UserQueryHandler $userQueryHandler,
        JWTFactory $JWTFactory
    )
    {
        $this->httpResponseFactory = $httpResponseFactory;
        $this->userQueryHandler = $userQueryHandler;
        $this->JWTFactory = $JWTFactory;
    }

    public function handle(): HttpResponse
    {
        $request = Request::createFromGlobals();
        $query = UserQuery::fromCredentials(
            (string)$request->get('username'),
            (string)$request->get('password')
        );
        $user = $this->userQueryHandler->handle($query);
        if ($user === null) {
            return $this->httpResponseFactory->create(JsonBadRequestResponse::create(), $request);
        }
        $apiKey = $this->JWTFactory->createFromUser($user);
        $apiResponse = JsonSuccessResponse::fromData(['apiKey' => $apiKey]);
        return $this->httpResponseFactory->create($apiResponse, $request);
    }

    public static function getSchema(): EndpointSchema
    {
        $urlFragments = UrlFragments::fromStrings(['auth', 'authenticate']);
        $endpointSchema = EndpointSchema::create(RequestMethod::post(), $urlFragments);
        $endpointSchema = $endpointSchema->setSummary('Authenticates a user.');
        $endpointSchema = $endpointSchema->setTags(['Authentication']);
        $endpointSchema = $endpointSchema->addRequestBodyParam(RequestParameterSchema::createString('username')->setRequired());
        $endpointSchema = $endpointSchema->addRequestBodyParam(RequestParameterSchema::createString('password')->setRequired());
        return $endpointSchema;
    }
}
