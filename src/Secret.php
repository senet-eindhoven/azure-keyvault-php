<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault;

use Psr\Http\Client\RequestExceptionInterface;
use Senet\AzureKeyVault\Exception\NotFoundException;
use Senet\AzureKeyVault\Mapper\DataToSecret;
use Senet\AzureKeyVault\Mapper\DataToSecretList;
use Senet\AzureKeyVault\Model\Secret as SecretModel;
use Senet\AzureKeyVault\Model\SecretList;

class Secret extends KeyVault
{
    /** @return SecretList[] */
    public function listSecrets(): iterable
    {
        try {
            $endpoint = sprintf(
                '%s/secrets?api-version=' . $this->getApiVersion(),
                $this->vaultUrl,
            );
            $data = $this->request($endpoint, 'GET');

            $list = [];
            foreach ($data['value'] as $secretData) {
                $list[] = DataToSecretList::map($secretData);
            }
            return $list;
        } catch (RequestExceptionInterface $e) {
            switch ($e->getCode()) {
                case 404:
                    throw new NotFoundException();
                default:
                    throw $e;
            }
        }
    }

    public function getSecret(string $id, string $version = null): SecretModel
    {
        try {
            $endpoint = sprintf(
                '%s/secrets/%s/%s?api-version=' . $this->getApiVersion(),
                $this->vaultUrl,
                $id,
                $version,
            );
            $data = $this->request($endpoint, 'GET');
            return DataToSecret::map($data);
        } catch (RequestExceptionInterface $e) {
            switch ($e->getCode()) {
                case 404:
                    throw NotFoundException::secretWithId($id);
                default:
                    throw $e;
            }
        }
    }
}
