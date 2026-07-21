<?php

declare(strict_types=1);

namespace App\Controller;

use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final readonly class InvoiceController
{
    public function __construct(private InvoiceGenerator $generator)
    {
    }

    #[Route('/invoices/example.pdf', name: 'invoice_example')]
    public function __invoke(): Response
    {
        $invoice = InvoiceBuilder::create()
            ->seller(PartyBuilder::create()->name('Empresa Symfony')->build())
            ->buyer(PartyBuilder::create()->name('Cliente Symfony')->build())
            ->number('FT-SYMFONY-001')
            ->currency('EUR')
            ->locale('pt_PT')
            ->addItem(
                ItemBuilder::create()
                    ->description('Desenvolvimento Symfony')
                    ->unitPrice(Money::fromDecimal('800.00', 'EUR'))
                    ->quantity(Quantity::fromInt(1))
                    ->tax(Percentage::fromBasisPoints(2300))
                    ->build(),
            )
            ->build();

        $document = $this->generator->generate($invoice, 'modern');

        return new Response($document->contents(), Response::HTTP_OK, [
            'Content-Type' => $document->mimeType(),
            'Content-Disposition' => 'attachment; filename="invoice.pdf"',
        ]);
    }
}
