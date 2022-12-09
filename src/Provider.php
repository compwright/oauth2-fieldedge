<?php

namespace Compwright\OAuth2_Fieldedge;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\UnencryptedToken;
use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class Provider extends AbstractProvider
{
    private Parser $parser;

    /**
     * Constructs an OAuth 2.0 service provider.
     *
     * @param array $options An array of options to set on this provider.
     *     Options include `clientId`, `clientSecret`, `redirectUri`, and `state`.
     *     Individual providers may introduce more options, as needed.
     * @param array $collaborators An array of collaborators that may be used to
     *     override this provider's default behavior. Collaborators include
     *     `grantFactory`, `requestFactory`, and `httpClient`.
     *     Individual providers may introduce more collaborators, as needed.
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        $this->parser = new Parser(new JoseEncoder());
        $collaborators['optionProvider'] = new HttpBasicAuthOptionProvider();
        parent::__construct($options, $collaborators);
    }

    public function getBaseAuthorizationUrl(): string
    {
        throw new RuntimeException('Not implemented');
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://api.fieldedge.com/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        throw new RuntimeException('Not implemented');
    }

    protected function getDefaultScopes(): array
    {
        return [];
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (isset($data['errorCode'], $data['message'])) {
            throw new IdentityProviderException(
                $data['message'],
                $data['errorCode'],
                $response
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        throw new RuntimeException('Not implemented');
    }

    /**
     * Prepares an parsed access token response for a grant.
     *
     * @param  mixed $result
     * @return array
     */
    protected function prepareAccessTokenResponse(array $result)
    {
        parent::prepareAccessTokenResponse($result);
        /** @var UnencryptedToken $jwt */
        $jwt = $this->parser->parse($result['access_token']);
        $result['resource_owner_id'] = $jwt->claims()->get('company_id');
        return $result;
    }
}
