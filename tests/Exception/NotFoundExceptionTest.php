<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Exception;
use PHPUnit\Framework\TestCase;

class NotFoundExceptionTest extends TestCase
{
    public function testIdMapping()
    {
        try {
            throw NotFoundException::secretWithId('my-id');
        } catch (NotFoundException $e) {
            $this->assertEquals('Unable to find secret with id: my-id', $e->getMessage());
        }
    }
}
