<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Client\ClientInterface;
use Senet\AzureKeyVault\Repository\SecretRepository;

final class KeyVault
{
    private ClientInterface $client;

    public function __construct(
        string $tenantName,
        private string $vaultUrl,
        string $clientId,
        string $clientSecret,
        private string $apiVersion = '7.3',
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
        } catch (ClientException $e) {
            throw new $e;
        }
    }

    public function getSecretRepository(): SecretRepository
    {
        return new SecretRepository(
            $this->client,
            $this->token,
            $this->vaultUrl,
            $this->apiVersion,
        );
    }
}
