<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault\Mapper;

use PHPUnit\Framework\TestCase;

class DataToSecretListTest extends TestCase
{
    public function testMappingOfDataArray(): void
    {
        $date = new \DateTimeImmutable();

        $data = [
            'id' => '1',
            'attributes' => [
                'enabled' => true,
                'created' => $date->getTimestamp(),
                'updated' => $date->getTimestamp(),
                'nbf' => $date->getTimestamp(),
                'exp' => $date->getTimestamp(),
            ],
        ];

        $model = DataToSecretList::map($data);

        $this->assertEquals('1', $model->getId());
        $this->assertTrue($model->isEnabled());
        $this->assertEquals($date->getTimestamp(), $model->getCreated()->getTimestamp());
        $this->assertEquals($date->getTimestamp(), $model->getUpdated()->getTimestamp());
        $this->assertEquals($date->getTimestamp(), $model->getNbf()->getTimestamp());
        $this->assertEquals($date->getTimestamp(), $model->getExp()->getTimestamp());
    }
}
