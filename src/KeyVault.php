<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault;

use Exception;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Senet\AzureKeyVault\Exception\RequestException;
use Webmozart\Assert\Assert;

class KeyVault
{
    protected const API_VERSION = '7.3';

    protected string $accessToken;

    public function __construct(
        string $tenantName,
        protected string $vaultUrl,
        string $clientId,
        string $clientSecret,
        protected ClientInterface $client,
        private array $options = [],
    ) {
        try {
            $this->validateOptions($this->options);
            $request = new Request(
                'POST',
                sprintf('https://login.microsoftonline.com/%s/oauth2/v2.0/token', $tenantName),
                [],
                \http_build_query([
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'scope' => 'https://vault.azure.net/.default',
                    'grant_type' => 'client_credentials',
                ]),
            );
            $response = $this->client->sendRequest($request);
            $token = $response->getBody()->getContents();
            $this->accessToken = json_decode($token, true)['access_token'];
        } catch (ClientExceptionInterface | Exception $e) {
            throw $e;
        }
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    private function validateOptions(array $options): void
    {
        foreach ($options as $option => $value) {
            switch ($option) {
                case 'api-version':
                    Assert::string($value, 'api-version requires `string`');
                    Assert::notEmpty($value, 'api-version cannot be empty');
                    break;
                default:
                    throw new InvalidArgumentException(
                        sprintf(
                            'invalid option `%s` given',
                            $option
                        )
                    );
            }
        }
    }

    public function getApiVersion(): string
    {
        if (isset($this->options['api-version']) === true) {
            return $this->options['api-version'];
        }

        return self::API_VERSION;
    }

    protected function request(
        string $endpoint,
        string $method,
    ): array {
        $request = new Request(
            $method,
            $endpoint,
            [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
        );
        $response = $this->client->sendRequest($request);
        $content = json_decode($response->getBody()->getContents(), true);
        if ($response->getStatusCode() !== 200) {
            throw new RequestException($content['error']['message'], $request, $response);
        }
        return $content;
    }
}
