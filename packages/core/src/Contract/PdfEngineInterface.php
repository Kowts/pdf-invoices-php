<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Contract;

use PdfInvoices\Core\Pdf\GeneratedDocument;
use PdfInvoices\Core\Pdf\PdfOptions;

interface PdfEngineInterface
{
    public function render(string $html, PdfOptions $options): GeneratedDocument;
}
