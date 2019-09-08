<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints\Auth;

use App\Packages\AccessManagement\Application\Query\AuthUserInformationByUserIdQuery;
use App\Packages\AccessManagement\Application\Query\AuthUserInformationByUserIdQueryHandler;
use App\WebApiV1Bundle\ApiRequest;
use App\WebApiV1Bundle\Authentication\JWTFactory;
use App\WebApiV1Bundle\Endpoints\Endpoint;
use App\WebApiV1Bundle\Response\HttpResponseFactory;
use App\WebApiV1Bundle\Response\JsonSuccessResponse;
use App\WebApiV1Bundle\Response\JsonUnauthorizedResponse;
use App\WebApiV1Bundle\Schema\EndpointSchema;
use App\WebApiV1Bundle\Schema\RequestMethod;
use App\WebApiV1Bundle\Schema\Parameter\ObjectParameterSchema;
use App\WebApiV1Bundle\Schema\Parameter\StringParameterSchema;
use App\WebApiV1Bundle\Schema\UrlFragments;
use App\WebApiV1Bundle\Transformers\UserTransformer;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class RefreshJwtEndpoint implements Endpoint
{
    private $httpResponseFactory;
    private $userInformationByUserIdQueryHandler;
    private $JWTFactory;
    private $userTransformer;

    public function __construct(
        HttpResponseFactory $httpResponseFactory,
        AuthUserInformationByUserIdQueryHandler $userInformationByUserIdQueryHandler,
        JWTFactory $JWTFactory,
        UserTransformer $userTransformer
    ) {
        $this->httpResponseFactory = $httpResponseFactory;
        $this->userInformationByUserIdQueryHandler = $userInformationByUserIdQueryHandler;
        $this->JWTFactory = $JWTFactory;
        $this->userTransformer = $userTransformer;
    }

    public function handle(): HttpResponse
    {
        $request = ApiRequest::createFromGlobals();
        $currentAuthUser = $request->getAuthUser();
        if($currentAuthUser->isAnonymous()) {
            return $this->createUnauthorizedResponse($request);
        }

        $jwt = $request->getJWT();
        if(!$jwt->canBeRefreshed()) {
            return $this->createUnauthorizedResponse($request);
        }

        $query = AuthUserInformationByUserIdQuery::fromUserId($currentAuthUser->getUserId()->toString());
        $authUserInformation = $this->userInformationByUserIdQueryHandler->handle($query);
        if($authUserInformation === null) {
            return $this->createUnauthorizedResponse($request);
        }

        $token = $this->JWTFactory->createFromAuthUser($authUserInformation->getAuthUser());
        $apiResponse = JsonSuccessResponse::fromData([
            'token' => $token,
            'user' => $this->userTransformer->transform($authUserInformation->getUser()),
        ]);
        return $this->httpResponseFactory->create($apiResponse, $request);
    }

    private function createUnauthorizedResponse(ApiRequest $request): HttpResponse
    {
        $response = JsonUnauthorizedResponse::create();
        return $this->httpResponseFactory->create($response, $request);
    }

    public static function getSchema(): EndpointSchema
    {
        $urlFragments = UrlFragments::fromStrings(['auth', 'authenticate']);
        $endpointSchema = EndpointSchema::create(RequestMethod::post(), $urlFragments);
        $endpointSchema = $endpointSchema->setSummary('Refreshes the auth token for a user.');
        $endpointSchema = $endpointSchema->setTags(['Auth']);
        $isRequired = true;
        $endpointSchema = $endpointSchema->setAuthKeyNeeded(true);
        $endpointSchema = $endpointSchema->addResponseSchema(
            JsonSuccessResponse::getSchema()->setResponseParameters(
                ObjectParameterSchema::create()
                    ->addProperty('token', StringParameterSchema::create(), $isRequired)
                    ->addProperty('user', UserTransformer::getReferenceModel()->getObjectParameter(), $isRequired)
            )
        );
        $endpointSchema = $endpointSchema->addResponseSchema(JsonUnauthorizedResponse::getSchema());
        return $endpointSchema;
    }
}
