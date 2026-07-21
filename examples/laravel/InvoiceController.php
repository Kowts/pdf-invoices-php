<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;

final readonly class InvoiceController
{
    public function __construct(private InvoiceGenerator $generator)
    {
    }

    public function download(): Response
    {
        $invoice = InvoiceBuilder::create()
            ->seller(PartyBuilder::create()->name('Empresa Laravel')->build())
            ->buyer(PartyBuilder::create()->name('Cliente Laravel')->build())
            ->number('FT-LARAVEL-001')
            ->currency('EUR')
            ->locale('pt_PT')
            ->addItem(
                ItemBuilder::create()
                    ->description('Desenvolvimento Laravel')
                    ->unitPrice(Money::fromDecimal('750.00', 'EUR'))
                    ->quantity(Quantity::fromInt(1))
                    ->tax(Percentage::fromBasisPoints(2300))
                    ->build(),
            )
            ->build();

        $document = $this->generator->generate($invoice, config('pdf-invoices.template', 'modern'));

        return response($document->contents(), 200, [
            'Content-Type' => $document->mimeType(),
            'Content-Disposition' => 'attachment; filename="invoice.pdf"',
        ]);
    }
}

