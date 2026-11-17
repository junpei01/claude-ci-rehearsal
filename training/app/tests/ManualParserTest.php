<?php

declare(strict_types=1);

namespace ManualConverter\Tests;

use ManualConverter\InputException;
use ManualConverter\ManualParser;
use PHPUnit\Framework\TestCase;

class ManualParserTest extends TestCase
{
    public function testParseSample(): void
    {
        $xml = (string) file_get_contents(__DIR__ . '/../../spec/samples/kt-200.xml');
        $manual = (new ManualParser())->parse($xml);

        $this->assertSame('業務用電気ケトル KT-200', $manual->productName);
        $this->assertSame('KT-200', $manual->modelNumber);
        $this->assertCount(3, $manual->sections);
        $this->assertSame('安全上のご注意', $manual->sections[0]->title);
        $this->assertCount(2, $manual->sections[0]->cautions);
    }

    public function testBrokenXml(): void
    {
        $this->expectException(InputException::class);
        (new ManualParser())->parse('<manual><meta>');
    }
}
