<?php
declare(strict_types=1);

namespace Senet\AzureKeyVault\Exception;

use RuntimeException;
use Throwable;

final class NotFoundException extends RuntimeException
{
    public static function secretWithId(string $id): self
    {
        return new self(
            sprintf(
                'Unable to find secret with id: %s',
                $id
            )
        );
    }
}