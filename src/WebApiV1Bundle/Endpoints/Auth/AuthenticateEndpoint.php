<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints\Auth;

use App\Packages\AccessManagement\Application\Query\AuthUserInformationByCredentialsQuery;
use App\Packages\AccessManagement\Application\Query\AuthUserInformationByCredentialsQueryHandler;
use App\WebApiV1Bundle\ApiRequest;
use App\WebApiV1Bundle\Authentication\JWTFactory;
use App\WebApiV1Bundle\Endpoints\Endpoint;
use App\WebApiV1Bundle\Response\HttpResponseFactory;
use App\WebApiV1Bundle\Response\JsonBadApiUsageResponse;
use App\WebApiV1Bundle\Response\JsonBadRequestResponse;
use App\WebApiV1Bundle\Response\JsonSuccessResponse;
use App\WebApiV1Bundle\Schema\EndpointSchema;
use App\WebApiV1Bundle\Schema\Parameter\ParameterValidator;
use App\WebApiV1Bundle\Schema\RequestMethod;
use App\WebApiV1Bundle\Schema\Parameter\ObjectParameterSchema;
use App\WebApiV1Bundle\Schema\Parameter\StringParameterSchema;
use App\WebApiV1Bundle\Schema\UrlFragments;
use App\WebApiV1Bundle\Transformers\UserTransformer;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class AuthenticateEndpoint implements Endpoint
{
    private $httpResponseFactory;
    private $parameterValidator;
    private $userInformationByCredentialsQueryHandler;
    private $JWTFactory;
    private $userTransformer;

    public function __construct(
        HttpResponseFactory $httpResponseFactory,
        ParameterValidator $parameterValidator,
        AuthUserInformationByCredentialsQueryHandler $userInformationByCredentialsQueryHandler,
        JWTFactory $JWTFactory,
        UserTransformer $userTransformer
    )
    {
        $this->httpResponseFactory = $httpResponseFactory;
        $this->parameterValidator = $parameterValidator;
        $this->userInformationByCredentialsQueryHandler = $userInformationByCredentialsQueryHandler;
        $this->JWTFactory = $JWTFactory;
        $this->userTransformer = $userTransformer;
    }

    public function handle(): HttpResponse
    {
        $request = ApiRequest::createFromGlobals();
        $badApiUsageResponse = $this->findBadApiUsageResponse($request);
        if($badApiUsageResponse !== null) {
            return $this->httpResponseFactory->create($badApiUsageResponse, $request);
        }
        $requestData = $request->getContentData();
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

    private function findBadApiUsageResponse(ApiRequest $request): ?JsonBadApiUsageResponse
    {
        $contentData = $request->getContentData();
        $errors = (array)$this->parameterValidator->validate(
            self::getSchema()->getRequestBodyParameterSchema(),
            $contentData
        );
        if(count($errors) === 0) {
            return null;
        }
        return JsonBadApiUsageResponse::create()->addErrors($errors);
    }

    public static function getSchema(): EndpointSchema
    {
        $urlFragments = UrlFragments::fromStrings(['auth', 'authenticate']);
        $endpointSchema = EndpointSchema::create(RequestMethod::post(), $urlFragments);
        $endpointSchema = $endpointSchema->setSummary('Authenticate a user.');
        $endpointSchema = $endpointSchema->setTags(['Auth']);
        $isRequired = true;
        $endpointSchema = $endpointSchema->setRequestBodyParams(
            ObjectParameterSchema::create()
                ->addProperty('username', StringParameterSchema::create(), $isRequired)
                ->addProperty('password', StringParameterSchema::create(), $isRequired)
        );
        $endpointSchema = $endpointSchema->addResponseSchema(
            JsonSuccessResponse::getSchema()->setResponseParameters(
                ObjectParameterSchema::create()
                    ->addProperty('token', StringParameterSchema::create(), $isRequired)
                    ->addProperty('user', UserTransformer::getReferenceModel()->getObjectParameter(), $isRequired)
            )
        );
        $endpointSchema = $endpointSchema->addResponseSchema(JsonBadApiUsageResponse::getSchema());
        return $endpointSchema;
    }
}
