<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Mapper;

use Senet\AzureKeyVault\Helper;
use Senet\AzureKeyVault\Model\SecretList;

final class DataToSecretList
{
    public static function map(array $data): SecretList
    {
        return new SecretList(
            $data['id'],
            Helper::timestampToDateTimeImmutable($data['attributes']['created']),
            Helper::timestampToDateTimeImmutable($data['attributes']['updated']),
            isset($data['attributes']['nbf']) ? Helper::timestampToDateTimeImmutable(
                $data['attributes']['nbf']
            ) : null,
            isset($data['attributes']['exp']) ? Helper::timestampToDateTimeImmutable(
                $data['attributes']['exp']
            ) : null,
        );
    }
}
