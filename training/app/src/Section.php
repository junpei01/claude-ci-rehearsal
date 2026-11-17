<?php

declare(strict_types=1);

namespace ManualConverter;

class Section
{
    /** @var string[] */
    public array $paragraphs = [];

    /** @var string[] */
    public array $cautions = [];

    /** @var string[][] */
    public array $lists = [];

    /** @var Section[] */
    public array $sections = [];

    public function __construct(
        public string $title,
        public int $level,
    ) {
    }

    public function hasContent(): bool
    {
        return $this->paragraphs !== []
            || $this->cautions !== []
            || $this->lists !== []
            || $this->sections !== [];
    }
}
