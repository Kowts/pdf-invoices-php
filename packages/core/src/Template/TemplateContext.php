<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Template;

use PdfInvoices\Core\Calculation\InvoiceTotals;
use PdfInvoices\Core\Contract\CurrencyFormatterInterface;
use PdfInvoices\Core\Contract\TranslatorInterface;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\Pdf\PdfOptions;

final readonly class TemplateContext
{
    /**
     * @param array<string, scalar|null> $theme
     */
    public function __construct(
        public Invoice $invoice,
        public InvoiceTotals $totals,
        public TranslatorInterface $translator,
        public CurrencyFormatterInterface $currencyFormatter,
        public PdfOptions $pdfOptions,
        public array $theme = [],
    ) {
    }
}

