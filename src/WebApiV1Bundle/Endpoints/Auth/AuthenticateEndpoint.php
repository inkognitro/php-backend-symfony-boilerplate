<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints\Auth;

use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserByCredentialsQuery;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserByCredentialsQueryHandler;
use App\WebApiV1Bundle\Authentication\JWTFactory;
use App\WebApiV1Bundle\Endpoints\Endpoint;
use App\WebApiV1Bundle\Response\HttpResponseFactory;
use App\WebApiV1Bundle\Response\JsonBadRequestResponse;
use App\WebApiV1Bundle\Response\JsonSuccessResponse;
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
        AuthUserByCredentialsQueryHandler $userQueryHandler,
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
        $query = AuthUserByCredentialsQuery::fromCredentials(
            (string)$request->get('username'),
            (string)$request->get('password')
        );
        $authUser = $this->userQueryHandler->handle($query);
        if ($authUser === null) {
            return $this->httpResponseFactory->create(JsonBadRequestResponse::create(), $request);
        }
        $apiKey = $this->JWTFactory->createFromUser($authUser);
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
