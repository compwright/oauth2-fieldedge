<?php

declare(strict_types=1);

namespace Compwright\OAuth2\FieldEdge;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class ProviderTest extends TestCase
{
    public function testTargetIdRequired(): void
    {
        $client = $this->createStub(ClientInterface::class);

        $provider = new ProviderFactory($client)->new(
            clientId: 'mock_client_id',
            clientSecret: 'mock_secret',
        );

        $this->expectExceptionMessageIsOrContains('Required parameter not passed: "target_id"');

        $provider->getAccessToken('client_credentials');
    }

    public function testProvider(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('send')
            ->with($this->callback(function (RequestInterface $request): bool {
                $body = (string) $request->getBody();

                return
                    'POST' === $request->getMethod()
                    && ProviderFactory::TOKEN_ENDPOINT === (string) $request->getUri()
                    && 'client_id=mock_client_id&client_secret=mock_secret&grant_type=client_credentials&target_id=mock_target_id' === $body;
            }))
            ->willReturn(
                new Response(
                    200,
                    ['content-type' => 'application/json'],
                    '{"access_token":"mock_access_token"}'
                )
            )
        ;

        $provider = new ProviderFactory($client)->new(
            clientId: 'mock_client_id',
            clientSecret: 'mock_secret',
        );

        $token = $provider->getAccessToken('client_credentials', [
            'target_id' => 'mock_target_id',
        ]);

        $this->assertEquals('mock_access_token', $token->getToken());
    }

    public function testLegacyProvider(): void
    {
        $client = $this->createMock(ClientInterface::class);
        $client->expects($this->once())
            ->method('send')
            ->with($this->callback(function (RequestInterface $request): bool {
                $body = (string) $request->getBody();

                return
                    'POST' === $request->getMethod()
                    && ProviderFactory::TOKEN_ENDPOINT_LEGACY === (string) $request->getUri()
                    && 'grant_type=client_credentials' === $body
                    && 'Basic '.base64_encode('mock_client_id:mock_secret') === $request->getHeaderLine('authorization');
            }))
            ->willReturn(
                new Response(
                    200,
                    ['content-type' => 'application/json'],
                    '{"access_token":"mock_access_token"}'
                )
            )
        ;

        $provider = new ProviderFactory($client)->newLegacy(
            clientId: 'mock_client_id',
            apiKey: 'mock_secret',
        );

        $token = $provider->getAccessToken('client_credentials');

        $this->assertEquals('mock_access_token', $token->getToken());
    }
}
