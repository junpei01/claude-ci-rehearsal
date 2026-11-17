<?php

declare(strict_types=1);

namespace ManualConverter;

/**
 * Manual を HTML にする
 */
class HtmlRenderer
{
    /** @var int[] 階層ごとの番号 */
    private array $counters = [];

    public function render(Manual $manual): string
    {
        $this->counters = [];

        $html = "<!DOCTYPE html>\n<html lang=\"ja\">\n<head>\n<meta charset=\"UTF-8\">\n";
        $html .= '<title>' . $this->e($manual->productName) . "</title>\n</head>\n<body>\n";
        $html .= '<h1>' . $this->e($manual->productName) . "</h1>\n";
        $html .= "<table>\n";
        $html .= '<tr><th>型番</th><td>' . $this->e($manual->modelNumber) . "</td></tr>\n";
        $html .= '<tr><th>改訂日</th><td>' . $this->e($manual->revisionDate) . "</td></tr>\n";
        $html .= "</table>\n";

        foreach ($manual->sections as $section) {
            $html .= $this->section($section);
        }

        $html .= "</body>\n</html>\n";
        return $html;
    }

    private function section(Section $section): string
    {
        if (!$section->hasContent()) {
            return '';
        }

        $level = $section->level;
        $this->counters[$level] = ($this->counters[$level] ?? 0) + 1;

        $number = [];
        for ($i = 1; $i <= $level; $i++) {
            $number[] = $this->counters[$i] ?? 1;
        }

        $tag = 'h' . ($level + 1);
        $html = "<{$tag}>" . implode('.', $number) . ' ' . $this->e($section->title) . "</{$tag}>\n";

        foreach ($section->paragraphs as $p) {
            $html .= '<p>' . $this->e($p) . "</p>\n";
        }
        foreach ($section->cautions as $c) {
            $html .= '<p>' . $this->e($c) . "</p>\n";
        }
        foreach ($section->lists as $items) {
            $html .= "<ul>\n";
            foreach ($items as $item) {
                $html .= '<li>' . $this->e($item) . "</li>\n";
            }
            $html .= "</ul>\n";
        }
        foreach ($section->sections as $child) {
            $html .= $this->section($child);
        }

        return $html;
    }

    private function e(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
