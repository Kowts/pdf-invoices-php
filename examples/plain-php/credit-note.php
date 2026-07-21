<?php

declare(strict_types=1);

use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Storage\LocalStorage;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$invoice = InvoiceBuilder::create()
    ->seller(PartyBuilder::create()->name('Empresa Exemplo, Lda.')->taxNumber('NIF 123456789')->build())
    ->buyer(PartyBuilder::create()->name('Cliente Exemplo')->taxNumber('NIF 987654321')->build())
    ->number('NC 2026/001')
    ->currency('EUR')
    ->locale('pt_PT')
    ->set('credit_note', true)
    ->addItem(
        ItemBuilder::create()
            ->description('Estorno parcial de servicos')
            ->unitPrice(Money::fromDecimal('-50.00', 'EUR'))
            ->quantity(Quantity::fromInt(1))
            ->tax(Percentage::fromBasisPoints(2300))
            ->build(),
    )
    ->notes('Documento emitido para corrigir a fatura FT 2026/001.')
    ->build();

$document = InvoiceGenerator::defaultHtmlPreview()->generate($invoice, 'modern');
$path = $document->store(new LocalStorage(dirname(__DIR__, 2) . '/build'), 'credit-note.html');

echo "Generated {$path}\n";

