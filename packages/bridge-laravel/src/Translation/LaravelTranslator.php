<?php

declare(strict_types=1);

namespace PdfInvoices\Laravel\Translation;

use Illuminate\Contracts\Translation\Translator;
use PdfInvoices\Core\Contract\TranslatorInterface;

final readonly class LaravelTranslator implements TranslatorInterface
{
    public function __construct(private Translator $translator)
    {
    }

    public function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $line = $this->translator->get("pdf-invoices::invoice.{$key}", $replace, $locale);

        return is_string($line) ? $line : $key;
    }
}

