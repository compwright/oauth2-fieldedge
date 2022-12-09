<?php

namespace Compwright\OAuth2_Fieldedge;

use GuzzleHttp\ClientInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\ResourceOwnerAccessTokenInterface;
use League\OAuth2\Client\Tool\QueryBuilderTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class FieldEdgeTest extends TestCase
{
    use QueryBuilderTrait;

    protected $provider;

    protected function setUp(): void
    {
        $this->provider = new Provider([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_api_key',
        ]);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('/token', $uri['path']);
    }

    public function testGetAccessToken()
    {
        /** @var MockObject&ResponseInterface */
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')->willReturn('{"access_token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiI2ZjhlZmI5Zi0yNDgxLTQzMGItYTY3ZS0xYjhkOWQ5NWM4YzYiLCJzdWIiOiI0YTIzYTI1YmFiZjc0MDlhODkzMTFhZjY3YWJhZGU5MSIsInBhcnRuZXJfbmFtZSI6IlRvIFlvdXIgU3VjY2VzcyIsImNvbXBhbnlfaWQiOiI4NjBjNTkzYjhjYmE0MGIyOTAzN2Y0MmNjMzg5ZWE0OSIsImNvbXBhbnlfbmFtZSI6IlBhcnRuZXJfVG9Zb3VyU3VjY2VzcyIsImRlcGxveW1lbnRfc2xvdCI6IjIiLCJDdXN0b21lcnMiOiJHZXQiLCJUcmFuc2FjdGlvbnMiOiJHZXQiLCJJdGVtQ2F0ZWdvcmllcyI6WyJHZXQiLCJQb3N0Il0sIlNhbGVzQ29tcGxldGVkV29ya3MiOiJHZXQiLCJRdW90ZUxpbmVJdGVtcyI6IkdldCIsIlF1b3RlcyI6IkdldCIsIkFncmVlbWVudHMiOiJHZXQiLCJUcmFuc2FjdGlvbkxpbmVJdGVtcyI6IkdldCIsIlRlY2hDb21wbGV0ZWRXb3JrcyI6IkdldCIsImV4cCI6MTY3MDYxMjY0NiwiaXNzIjoiRmllbGRFZGdlLlNlY3VyaXR5LkJlYXJlciIsImF1ZCI6IkZpZWxkRWRnZS5TZWN1cml0eS5CZWFyZXIifQ.BN6YLtClZVE6rghrHvfNYz2QT4R1dnzhxSmW2XPhhTc","expires_in":1800,"token_type":"Bearer"}');
        $response->method('getHeader')->willReturn(['content-type' => 'json']);

        /** @var MockObject&ClientInterface */
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())->method('send')->willReturn($response);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('client_credentials');

        $this->assertInstanceOf(ResourceOwnerAccessTokenInterface::class, $token);
        $this->assertLessThanOrEqual(time() + 1800, $token->getExpires());
        $this->assertGreaterThanOrEqual(time(), $token->getExpires());
        $this->assertEquals('860c593b8cba40b29037f42cc389ea49', $token->getResourceOwnerId());
    }

    public function testOauth2Error()
    {
        $this->expectException(IdentityProviderException::class);

        /** @var MockObject&ResponseInterface */
        $response = $this->createStub(ResponseInterface::class);
        $response->method('getBody')->willReturn('{"message": "The Partner Key and Company Key pair do not match any associated company record.  Please contact support.","errorCode": 10000006,"errorId": "a297594f-05d7-4e67-8b05-bf3e590d50bb"}');
        $response->method('getHeader')->willReturn(['content-type' => 'json']);
        $response->method('getStatusCode')->willReturn(401);

        /** @var MockObject&ClientInterface */
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('send')
            ->willReturn($response);
        $this->provider->setHttpClient($client);

        $this->provider->getAccessToken('client_credentials');
    }
}
