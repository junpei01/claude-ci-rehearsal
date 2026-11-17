<?php

declare(strict_types=1);

namespace ManualConverter\Tests;

use ManualConverter\Manual;
use ManualConverter\ManualValidator;
use PHPUnit\Framework\TestCase;

class ManualValidatorTest extends TestCase
{
    public function testValid(): void
    {
        $manual = new Manual('電気ケトル', 'KT-200', '2026-10-01');
        $this->assertSame([], (new ManualValidator())->validate($manual));
    }

    public function testNoProductName(): void
    {
        $manual = new Manual('', 'KT-200', '2026-10-01');
        $this->assertSame(['製品名がありません'], (new ManualValidator())->validate($manual));
    }

    public function testBadDate(): void
    {
        $manual = new Manual('電気ケトル', 'KT-200', '2026年10月1日');
        $errors = (new ManualValidator())->validate($manual);
        $this->assertCount(1, $errors);
    }
}
