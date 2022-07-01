<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault;

use DateTimeImmutable;

class Helper
{
    public static function timestampToDateTimeImmutable(int $timestamp): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('U', (string)$timestamp);
    }
}
