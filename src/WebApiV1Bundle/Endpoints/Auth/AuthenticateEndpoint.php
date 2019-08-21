<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints\Auth;

use App\Packages\AccessManagement\Application\Query\AuthUserInformationByCredentialsQuery;
use App\Packages\AccessManagement\Application\Query\AuthUserInformationByCredentialsQueryHandler;
use App\WebApiV1Bundle\ApiRequest;
use App\WebApiV1Bundle\Authentication\JWTFactory;
use App\WebApiV1Bundle\Endpoints\Endpoint;
use App\WebApiV1Bundle\Response\HttpResponseFactory;
use App\WebApiV1Bundle\Response\JsonBadRequestResponse;
use App\WebApiV1Bundle\Response\JsonSuccessResponse;
use App\WebApiV1Bundle\Schema\EndpointSchema;
use App\WebApiV1Bundle\Schema\RequestMethod;
use App\WebApiV1Bundle\Schema\RequestParameterSchema;
use App\WebApiV1Bundle\Schema\UrlFragments;
use App\WebApiV1Bundle\Transformers\UserTransformer;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class AuthenticateEndpoint implements Endpoint
{
    private $httpResponseFactory;
    private $userInformationByCredentialsQueryHandler;
    private $JWTFactory;
    private $userTransformer;

    public function __construct(
        HttpResponseFactory $httpResponseFactory,
        AuthUserInformationByCredentialsQueryHandler $userInformationByCredentialsQueryHandler,
        JWTFactory $JWTFactory,
        UserTransformer $userTransformer
    )
    {
        $this->httpResponseFactory = $httpResponseFactory;
        $this->userInformationByCredentialsQueryHandler = $userInformationByCredentialsQueryHandler;
        $this->JWTFactory = $JWTFactory;
        $this->userTransformer = $userTransformer;
    }

    public function handle(): HttpResponse
    {
        $request = ApiRequest::createFromGlobals();
        $requestData = $request->getContentData();

        //todo: validate requestData structure via schema

        $query = AuthUserInformationByCredentialsQuery::fromCredentials(
            $requestData['username'],
            $requestData['password'],
            $request->getLanguageId()
        );
        $authUserInformation = $this->userInformationByCredentialsQueryHandler->handle($query);
        if ($authUserInformation === null) {
            return $this->httpResponseFactory->create(JsonBadRequestResponse::create(), $request);
        }
        $apiKey = $this->JWTFactory->createFromAuthUser($authUserInformation->getAuthUser());
        $apiResponse = JsonSuccessResponse::fromData([
            'apiKey' => $apiKey,
            'user' => $this->userTransformer->transform($authUserInformation->getUser()),
        ]);
        return $this->httpResponseFactory->create($apiResponse, $request);
    }

    public static function getSchema(): EndpointSchema
    {
        $urlFragments = UrlFragments::fromStrings(['auth', 'authenticate']);
        $endpointSchema = EndpointSchema::create(RequestMethod::post(), $urlFragments);
        $endpointSchema = $endpointSchema->setSummary('Authenticate a user.');
        $endpointSchema = $endpointSchema->setTags(['Auth']);
        $endpointSchema = $endpointSchema->addRequestBodyParam(RequestParameterSchema::createString('username')->setRequired());
        $endpointSchema = $endpointSchema->addRequestBodyParam(RequestParameterSchema::createString('password')->setRequired());
        return $endpointSchema;
    }
}
