<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class KeyVaultTest extends TestCase
{
    private const JWT_TOKEN = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

    private function getSuccessClientMock(): ClientInterface
    {
        $mock = new MockHandler([
            new Response(
                200,
                [],
                '{"token_type":"Bearer","expires_in":3599,"ext_expires_in":3599,"access_token":"'.self::JWT_TOKEN.'"}'
            ),
        ]);
        $handlerStack = HandlerStack::create($mock);
        return new Client(['handler' => $handlerStack]);
    }

    public function testValidOAuthAuthentication(): void
    {
        $keyvault = new KeyVault(
            'tenant',
            'https://my-vault-uri.io',
            'clientId',
            'clientSecret',
            $this->getSuccessClientMock(),
        );
        $this->assertEquals($keyvault->getAccessToken(), self::JWT_TOKEN);
    }

    public function testAuthenticationFailure(): void
    {
        $mock = new MockHandler([
            new ClientException(
                'Error Communicating with Server',
                new Request('GET', 'test'),
                new Response(400)
            )
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        $this->expectException(ClientExceptionInterface::class);
        new KeyVault(
            'tenant',
            'https://my-vault-uri.io',
            'clientId',
            'clientSecret',
            $client,
        );
    }

    public function testInvalidOption(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new KeyVault(
            'tenant',
            'https://my-vault-uri.io',
            'clientId',
            'clientSecret',
            $this->getSuccessClientMock(),
            [
                'invalid' => 'option',
            ]
        );
    }

    public function testSettingCustomApiVersion(): void
    {
        $keyVault = new KeyVault(
            'tenant',
            'https://my-vault-uri.io',
            'clientId',
            'clientSecret',
            $this->getSuccessClientMock(),
            [
                'api-version' => '7.0',
            ]
        );
        $this->assertEquals('7.0', $keyVault->getApiVersion());

        foreach (['', 1] as $value) {
            $this->expectException(InvalidArgumentException::class);
            new KeyVault(
                'tenant',
                'https://my-vault-uri.io',
                'clientId',
                'clientSecret',
                $this->getSuccessClientMock(),
                [
                    'api-version' => $value,
                ]
            );
        }
    }
}
