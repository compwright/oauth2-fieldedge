<?php

declare(strict_types=1);

namespace Compwright\OAuth2\FieldEdge;

use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Grant\ClientCredentials;
use League\OAuth2\Client\Grant\GrantFactory;
use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use League\OAuth2\Client\Provider\GenericProvider;

class ProviderFactory
{
    public const TOKEN_ENDPOINT_LEGACY = 'https://api.fieldedge.com/token';

    public const TOKEN_ENDPOINT = 'https://fieldedge.withgobo.com/oauth/token';

    public function __construct(private ?ClientInterface $httpClient = null)
    {
    }

    public function newLegacy(
        ?string $clientId = null,
        ?string $apiKey = null
    ): GenericProvider {
        return new GenericProvider([
            'clientId' => $clientId,
            'clientSecret' => $apiKey,
            'urlAccessToken' => self::TOKEN_ENDPOINT_LEGACY,
            'urlAuthorize' => '',
            'urlResourceOwnerDetails' => '',
            'responseError' => 'message',
            'responseCode' => 'errorCode',
        ], [
            'httpClient' => $this->httpClient,

            // Only allow client_credentials grant, and require target_id parameter
            'grantFactory' => new GrantFactory()
                ->setGrant('client_credentials', new ClientCredentials()),

            // Send client ID and secret via HTTP Basic auth scheme
            'optionProvider' => new HttpBasicAuthOptionProvider(),
        ]);
    }

    public function new(
        ?string $clientId = null,
        ?string $clientSecret = null,
    ): GenericProvider {
        return new GenericProvider([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'urlAccessToken' => self::TOKEN_ENDPOINT,
            'urlAuthorize' => '',
            'urlResourceOwnerDetails' => '',
        ], [
            'httpClient' => $this->httpClient,

            // Only allow client_credentials grant, and require target_id parameter
            'grantFactory' => new GrantFactory()
                ->setGrant('client_credentials', new ClientCredentialsWithTargetId()),
        ]);
    }
}
