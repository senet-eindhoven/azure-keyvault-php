<?php

declare(strict_types=1);

namespace Senet\AzureKeyVault;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testTimestampToDateTimeImmutable(): void
    {
        $dateString = '2022-07-01 10:38:00';
        $timestamp = (new DateTimeImmutable($dateString))->getTimestamp();
        $datetime = Helper::timestampToDateTimeImmutable($timestamp);

        $this->assertEquals($dateString, $datetime->format('Y-m-d H:i:s'));
    }
}
