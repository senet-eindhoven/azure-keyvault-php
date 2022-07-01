<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Model;

use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testGetters(): void
    {
        $tag = new Tag('a', 'b');
        $this->assertEquals('a', $tag->getKey());
        $this->assertEquals('b', $tag->getValue());
    }
}
