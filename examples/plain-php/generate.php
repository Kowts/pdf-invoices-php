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

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$seller = PartyBuilder::create()
    ->name('Empresa Exemplo, Lda.')
    ->taxNumber('NIF 123456789')
    ->email('faturacao@example.test')
    ->build();

$buyer = PartyBuilder::create()
    ->name('Cliente Exemplo')
    ->taxNumber('NIF 987654321')
    ->email('cliente@example.test')
    ->build();

$invoice = InvoiceBuilder::create()
    ->seller($seller)
    ->buyer($buyer)
    ->number('FT 2026/001')
    ->currency('CVE')
    ->locale('pt_PT')
    ->addItem(
        ItemBuilder::create()
            ->description('Serviços profissionais')
            ->unitPrice(Money::fromDecimal('1500.00', 'CVE'))
            ->quantity(Quantity::fromDecimal('2.5'))
            ->tax(Percentage::fromBasisPoints(1500))
            ->build(),
    )
    ->notes('Pagamento a 30 dias.')
    ->build();

$document = InvoiceGenerator::defaultHtmlPreview()->generate($invoice, 'modern');
$document->store(new LocalStorage(__DIR__ . '/../../build'), 'invoice-preview.html');

echo "Generated build/invoice-preview.html\n";

