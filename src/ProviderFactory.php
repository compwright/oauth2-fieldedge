<?php

declare(strict_types=1);

namespace Compwright\OAuth2\FieldEdge;

use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Grant\GrantFactory;
use League\OAuth2\Client\Provider\GenericProvider;

class ProviderFactory
{
    public const TOKEN_ENDPOINT = 'https://fieldedge.withgobo.com/oauth/token';

    public function __construct(private ?ClientInterface $httpClient = null)
    {
    }

    public function new(
        ?string $clientId = null,
        ?string $clientSecret = null,
    ): GenericProvider {
        // Only allow client_credentials grant, and require target_id parameter
        $grantFactory = new GrantFactory();
        $grantFactory->setGrant('client_credentials', new ClientCredentialsWithTargetId());

        return new GenericProvider([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'urlAccessToken' => self::TOKEN_ENDPOINT,
            'urlAuthorize' => '',
            'urlResourceOwnerDetails' => '',
        ], [
            'httpClient' => $this->httpClient,
            'grantFactory' => $grantFactory,
        ]);
    }
}
