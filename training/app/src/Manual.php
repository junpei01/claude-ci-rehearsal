<?php

declare(strict_types=1);

namespace ManualConverter;

class Manual
{
    /** @var Section[] */
    public array $sections = [];

    public function __construct(
        public string $productName,
        public string $modelNumber,
        public string $revisionDate,
    ) {
    }
}
