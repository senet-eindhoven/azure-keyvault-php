<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Exception;

use Exception;

class NotFoundException extends Exception
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
