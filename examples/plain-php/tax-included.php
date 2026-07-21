<?php

declare(strict_types=1);

use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Storage\LocalStorage;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$invoice = InvoiceBuilder::create()
    ->seller(PartyBuilder::create()->name('Loja Exemplo')->taxNumber('NIF 123456789')->build())
    ->buyer(PartyBuilder::create()->name('Consumidor Final')->build())
    ->number('FT 2026/IVA-INCLUIDO')
    ->currency('EUR')
    ->locale('pt_PT')
    ->addItem(
        ItemBuilder::create()
            ->description('Produto com IVA incluido')
            ->unitPrice(Money::fromDecimal('120.00', 'EUR'))
            ->quantity(Quantity::fromInt(1))
            ->taxIncluded()
            ->tax(Percentage::fromBasisPoints(2000))
            ->build(),
    )
    ->build();

$totals = (new InvoiceCalculator())->calculate($invoice);

echo 'Base tributavel: ' . $totals->taxableBase->toDecimalString() . " EUR\n";
echo 'Imposto: ' . $totals->tax->toDecimalString() . " EUR\n";
echo 'Total: ' . $totals->total->toDecimalString() . " EUR\n";

$document = InvoiceGenerator::defaultHtmlPreview()->generate($invoice, 'minimal');
$path = $document->store(new LocalStorage(dirname(__DIR__, 2) . '/build'), 'tax-included.html');

echo "Generated {$path}\n";

