<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Bridge\Laravel\Http;

use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class InvoiceResponseFactory
{
    public function __construct(private ResponseFactory $responses)
    {
    }

    public function download(string $contents, string $filename): StreamedResponse
    {
        return $this->responses->streamDownload(
            static function () use ($contents): void {
                echo $contents;
            },
            $filename,
            ['Content-Type' => 'application/pdf'],
        );
    }
}
