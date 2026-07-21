<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Bridge\Support;

use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\ValueObject\Money;

final class InvoiceFactory
{
    public static function make(): Invoice
    {
        return InvoiceBuilder::create()
            ->seller(PartyBuilder::create()->name('Seller')->build())
            ->buyer(PartyBuilder::create()->name('Buyer')->build())
            ->number('FT 2026/001')
            ->currency('EUR')
            ->locale('pt_PT')
            ->addItem(
                ItemBuilder::create()
                    ->description('Consultoria')
                    ->unitPrice(Money::fromMinor(1500, 'EUR'))
                    ->build(),
            )
            ->build();
    }
}
