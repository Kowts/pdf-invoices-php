<?php

declare(strict_types=1);

namespace PdfInvoices\Symfony\Http;

use PdfInvoices\Core\Pdf\GeneratedDocument;
use Symfony\Component\HttpFoundation\Response;

final class InvoiceResponseFactory
{
    public function download(GeneratedDocument $document, string $filename): Response
    {
        return new Response(
            $document->contents(),
            Response::HTTP_OK,
            [
                'Content-Type' => $document->mimeType(),
                'Content-Disposition' => sprintf('attachment; filename="%s"', addslashes($filename)),
            ],
        );
    }
}
