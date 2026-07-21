<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Pdf;

use PdfInvoices\Core\Contract\PdfEngineInterface;

final class HtmlPreviewEngine implements PdfEngineInterface
{
    public function render(string $html, PdfOptions $options): GeneratedDocument
    {
        return new GeneratedDocument($html, 'text/html');
    }
}

