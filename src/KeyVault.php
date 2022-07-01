<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;

class KeyVault
{
    protected ClientInterface $client;

    protected string $token;

    public function __construct(
        string $tenantName,
        protected string $vaultUrl,
        string $clientId,
        string $clientSecret,
        protected string $apiVersion = '7.3',
    ) {
        try {
            $this->client = new Client();
            $response = $this->client->post(
                sprintf('https://login.microsoftonline.com/%s.onmicrosoft.com/oauth2/v2.0/token', $tenantName),
                [
                    'form_params' => [
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'scope' => 'https://vault.azure.net/.default',
                        'grant_type' => 'client_credentials',
                    ],
                ]
            );
            $token = $response->getBody()->getContents();
            $this->token = json_decode($token, true)['access_token'];
        } catch (ClientException | Exception $e) {
            throw new $e();
        }
    }

    protected function request(
        string $endpoint,
        string $method,
    ) {
        $response = $this->client->$method($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
