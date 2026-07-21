<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Pdf;

final readonly class PdfOptions
{
    /**
     * @param array<string, scalar|null> $metadata
     */
    public function __construct(
        public string $format = 'A4',
        public string $orientation = 'portrait',
        public int $marginTopMm = 12,
        public int $marginRightMm = 12,
        public int $marginBottomMm = 12,
        public int $marginLeftMm = 12,
        public bool $allowRemoteResources = false,
        public array $metadata = [],
    ) {
    }
}

