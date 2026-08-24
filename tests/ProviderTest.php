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

        $factory = new ProviderFactory($client);

        $provider = $factory->new(
            clientId: 'mock_client_id',
            clientSecret: 'mock_secret',
        );

        $this->expectExceptionMessage('Required parameter not passed: "target_id"');

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

        $factory = new ProviderFactory($client);

        $provider = $factory->new(
            clientId: 'mock_client_id',
            clientSecret: 'mock_secret',
        );

        $token = $provider->getAccessToken('client_credentials', [
            'target_id' => 'mock_target_id',
        ]);

        $this->assertEquals('mock_access_token', $token->getToken());
    }
}
