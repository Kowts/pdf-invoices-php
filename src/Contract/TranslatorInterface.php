<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Contract;

interface TranslatorInterface
{
    /**
     * @param array<string, scalar|null> $replace
     */
    public function trans(string $key, array $replace = [], ?string $locale = null): string;
}
