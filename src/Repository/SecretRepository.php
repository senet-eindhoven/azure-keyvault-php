<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Repository;

use Psr\Http\Client\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Senet\AzureKeyVault\Exception\NotFoundException;
use Senet\AzureKeyVault\Mapper\DataToSecret;
use Senet\AzureKeyVault\Mapper\DataToSecretList;
use Senet\AzureKeyVault\Model\Secret;
use Senet\AzureKeyVault\Model\SecretList;

class SecretRepository
{
    public function __construct(
        private ClientInterface $client,
        private string $token,
        private string $vaultUrl,
        private string $apiVersion,
    ) {
    }

    private function request(
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

    /** @return SecretList[] */
    public function listSecrets(): iterable
    {
        try {
            $endpoint = sprintf(
                '%s/secrets?api-version=' . $this->apiVersion,
                $this->vaultUrl,
            );
            $data = $this->request($endpoint, 'get');

            $list = [];
            foreach ($data['value'] as $secretData) {
                $list[] = DataToSecretList::map($secretData);
            }
            return $list;
        } catch (ClientException $e) {
            switch ($e->getCode()) {
                case 404:
                    throw new NotFoundException();
                default:
                    throw $e;
            }
        }
    }

    public function getSecret(string $id, string $version = null): ?Secret
    {
        try {
            $endpoint = sprintf(
                '%s/secrets/%s/%s?api-version=' . $this->apiVersion,
                $this->vaultUrl,
                $id,
                $version,
            );
            $data = $this->request($endpoint, 'get');
            return DataToSecret::map($data);
        } catch (ClientException $e) {
            switch ($e->getCode()) {
                case 404:
                    throw NotFoundException::secretWithId($id);
                default:
                    throw $e;
            }
        }
    }
}
