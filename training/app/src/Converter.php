<?php

declare(strict_types=1);

namespace ManualConverter;

/**
 * 読み込み → チェック → HTML
 */
class Converter
{
    /** @var string[] */
    public array $errors = [];

    public function toHtml(string $xml): ?string
    {
        $manual = (new ManualParser())->parse($xml);

        $this->errors = (new ManualValidator())->validate($manual);
        if ($this->errors !== []) {
            return null;
        }

        return (new HtmlRenderer())->render($manual);
    }
}
