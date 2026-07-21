<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests;

use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class InvoiceGeneratorTest extends TestCase
{
    public function testItGeneratesHtmlPreviewWithEscapedContent(): void
    {
        $invoice = InvoiceBuilder::create()
            ->seller(PartyBuilder::create()->name('<Seller>')->build())
            ->buyer(PartyBuilder::create()->name('Buyer')->build())
            ->currency('EUR')
            ->addItem(
                ItemBuilder::create()
                    ->description('<script>alert(1)</script>')
                    ->unitPrice(Money::fromMinor(1000, 'EUR'))
                    ->build(),
            )
            ->build();

        $document = InvoiceGenerator::defaultHtmlPreview()->generate($invoice, 'minimal');

        self::assertSame('text/html', $document->mimeType());
        self::assertStringContainsString('&lt;Seller&gt;', $document->contents());
        self::assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $document->contents());
    }
}

