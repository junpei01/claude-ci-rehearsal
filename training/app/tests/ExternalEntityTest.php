<?php

declare(strict_types=1);

namespace ManualConverter\Tests;

use ManualConverter\Converter;
use PHPUnit\Framework\TestCase;

/**
 * XML の外部実体参照で、手元のファイルの中身を読み込まないことの確認
 *
 * 外部実体を展開する設定（LIBXML_NOENT）を足すと、ここが落ちる。
 */
class ExternalEntityTest extends TestCase
{
    /** 読み込まれたかどうかを見分けるための目印 */
    private const MARK = 'REHEARSAL-SECRET-0001';

    private string $file = '';

    protected function setUp(): void
    {
        $this->file = (string) tempnam(sys_get_temp_dir(), 'ent');
        file_put_contents($this->file, self::MARK);
    }

    protected function tearDown(): void
    {
        if ($this->file !== '' && is_file($this->file)) {
            unlink($this->file);
        }
    }

    public function testFileContentIsNotInHtml(): void
    {
        $xml = $this->xmlWithEntity('<productName>製品 &x;</productName>', '2026-10-19');

        $html = (string) (new Converter())->toHtml($xml);

        $this->assertStringNotContainsString(self::MARK, $html);
    }

    public function testFileContentIsNotInErrors(): void
    {
        // 改訂日の値はエラーの文面に入るので、展開されるとここから漏れる
        $xml = $this->xmlWithEntity('<productName>製品</productName>', '&x;');

        $converter = new Converter();
        $converter->toHtml($xml);

        $this->assertStringNotContainsString(self::MARK, implode("\n", $converter->errors));
    }

    private function xmlWithEntity(string $productName, string $revisionDate): string
    {
        $uri = 'file://' . $this->file;

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE manual [<!ENTITY x SYSTEM "{$uri}">]>
<manual>
  <meta>
    {$productName}
    <modelNumber>KT-1</modelNumber>
    <revisionDate>{$revisionDate}</revisionDate>
  </meta>
  <body><section><title>見出し</title><para>本文</para></section></body>
</manual>
XML;
    }
}
