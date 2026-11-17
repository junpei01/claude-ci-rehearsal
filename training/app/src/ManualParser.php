<?php

declare(strict_types=1);

namespace ManualConverter;

use DOMDocument;
use DOMElement;

/**
 * XML を読み込んで Manual にする
 */
class ManualParser
{
    public function parse(string $xml): Manual
    {
        if (trim($xml) === '') {
            throw new InputException('XML を読み込めません');
        }

        $dom = new DOMDocument();

        libxml_use_internal_errors(true);
        $loaded = $dom->loadXML($xml, LIBXML_NONET|LIBXML_NOENT);
        libxml_clear_errors();

        if ($loaded === false || $dom->documentElement === null) {
            throw new InputException('XML を読み込めません');
        }

        $root = $dom->documentElement;
        $meta = $this->child($root, 'meta');

        $manual = new Manual(
            $this->text($meta, 'productName'),
            $this->text($meta, 'modelNumber'),
            $this->text($meta, 'revisionDate'),
        );

        $body = $this->child($root, 'body');
        if ($body !== null) {
            foreach ($this->children($body, 'section') as $el) {
                $manual->sections[] = $this->section($el, 1);
            }
        }

        return $manual;
    }

    private function section(DOMElement $el, int $level): Section
    {
        $section = new Section($this->text($el, 'title'), $level);

        foreach ($el->childNodes as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }
            switch ($node->tagName) {
                case 'para':
                    $section->paragraphs[] = trim($node->textContent);
                    break;
                case 'caution':
                    $section->cautions[] = trim($node->textContent);
                    break;
                case 'list':
                    $items = [];
                    foreach ($this->children($node, 'item') as $item) {
                        $items[] = trim($item->textContent);
                    }
                    $section->lists[] = $items;
                    break;
                case 'section':
                    $section->sections[] = $this->section($node, $level + 1);
                    break;
            }
        }

        return $section;
    }

    private function child(?DOMElement $parent, string $name): ?DOMElement
    {
        if ($parent === null) {
            return null;
        }
        foreach ($parent->childNodes as $node) {
            if ($node instanceof DOMElement && $node->tagName === $name) {
                return $node;
            }
        }
        return null;
    }

    /**
     * @return DOMElement[]
     */
    private function children(DOMElement $parent, string $name): array
    {
        $result = [];
        foreach ($parent->childNodes as $node) {
            if ($node instanceof DOMElement && $node->tagName === $name) {
                $result[] = $node;
            }
        }
        return $result;
    }

    private function text(?DOMElement $parent, string $name): string
    {
        $el = $this->child($parent, $name);
        return $el === null ? '' : trim($el->textContent);
    }
}
