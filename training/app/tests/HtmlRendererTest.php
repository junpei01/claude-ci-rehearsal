<?php

declare(strict_types=1);

namespace ManualConverter\Tests;

use ManualConverter\HtmlRenderer;
use ManualConverter\Manual;
use ManualConverter\Section;
use PHPUnit\Framework\TestCase;

class HtmlRendererTest extends TestCase
{
    public function testTitle(): void
    {
        $manual = new Manual('電気ケトル', 'KT-200', '2026-10-01');
        $section = new Section('はじめに', 1);
        $section->paragraphs[] = 'お買い上げありがとうございます。';
        $manual->sections[] = $section;

        $html = (new HtmlRenderer())->render($manual);

        $this->assertStringContainsString('<h1>電気ケトル</h1>', $html);
        $this->assertStringContainsString('<h2>1 はじめに</h2>', $html);
    }

    public function testEscape(): void
    {
        $manual = new Manual('A & B', 'KT-200', '2026-10-01');
        $html = (new HtmlRenderer())->render($manual);

        $this->assertStringContainsString('<h1>A &amp; B</h1>', $html);
    }
}
