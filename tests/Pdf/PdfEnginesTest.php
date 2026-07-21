<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Pdf;

use Mpdf\Mpdf;
use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Pdf\BrowsershotEngine;
use PdfInvoices\Core\Pdf\MpdfEngine;
use PdfInvoices\Core\Pdf\PdfOptions;
use PdfInvoices\Core\Pdf\TcpdfEngine;
use PHPUnit\Framework\TestCase;
use TCPDF;

final class PdfEnginesTest extends TestCase
{
    public function testMpdfEngineRendersPdfContents(): void
    {
        if (! class_exists(Mpdf::class)) {
            self::markTestSkipped('mPDF is not installed.');
        }

        $document = (new MpdfEngine())->render('<h1>Invoice</h1>', new PdfOptions());

        self::assertSame('application/pdf', $document->mimeType());
        self::assertStringStartsWith('%PDF-', $document->contents());
    }

    public function testTcpdfEngineRendersPdfContents(): void
    {
        if (! class_exists(TCPDF::class)) {
            self::markTestSkipped('TCPDF is not installed.');
        }

        $document = (new TcpdfEngine())->render('<h1>Invoice</h1>', new PdfOptions());

        self::assertSame('application/pdf', $document->mimeType());
        self::assertStringStartsWith('%PDF-', $document->contents());
    }

    public function testBrowsershotEngineImplementsPdfEngineContract(): void
    {
        self::assertInstanceOf(PdfEngineInterface::class, new BrowsershotEngine());
    }
}
