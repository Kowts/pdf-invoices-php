<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Bridge\Support;

use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Pdf\GeneratedDocument;
use PdfInvoices\Core\Pdf\PdfOptions;

final class BridgeHtmlPdfEngine implements PdfEngineInterface
{
    public function render(string $html, PdfOptions $options): GeneratedDocument
    {
        return new GeneratedDocument($html, 'text/html');
    }
}
