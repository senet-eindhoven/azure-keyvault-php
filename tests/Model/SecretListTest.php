<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Model;

use PHPUnit\Framework\TestCase;

class SecretListTest extends TestCase
{
    public function testGetters(): void
    {
        $date = new \DateTimeImmutable('2022-07-01 10:11:12');
        $model = new SecretList(
            'id',
            $date,
            $date,
            $date,
            $date
        );
        $this->assertEquals('id', $model->getId());
        $this->assertEquals($date->getTimestamp(), $model->getCreated()->getTimestamp());
        $this->assertEquals($date->getTimestamp(), $model->getUpdated()->getTimestamp());
        $this->assertEquals($date->getTimestamp(), $model->getNbf()->getTimestamp());
        $this->assertEquals($date->getTimestamp(), $model->getExp()->getTimestamp());
    }
}
