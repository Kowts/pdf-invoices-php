<?php

declare(strict_types=1);

use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\Domain\Address;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

return static function (): Invoice {
    $seller = PartyBuilder::create()
        ->name('Empresa Exemplo, Lda.')
        ->taxNumber('NIF 123456789')
        ->email('faturacao@example.test')
        ->phone('+238 260 00 00')
        ->address(new Address(
            line1: 'Rua da Liberdade, 10',
            postalCode: '7600',
            city: 'Praia',
            country: 'CV',
        ))
        ->build();

    $buyer = PartyBuilder::create()
        ->name('Cliente Exemplo')
        ->taxNumber('NIF 987654321')
        ->email('cliente@example.test')
        ->address(new Address(
            line1: 'Avenida Principal, 25',
            city: 'Mindelo',
            country: 'CV',
        ))
        ->build();

    return InvoiceBuilder::create()
        ->seller($seller)
        ->buyer($buyer)
        ->number('FT 2026/001')
        ->issuedAt(new DateTimeImmutable('2026-07-21'))
        ->dueAt(new DateTimeImmutable('2026-08-20'))
        ->currency('CVE')
        ->locale('pt_PT')
        ->addItem(
            ItemBuilder::create()
                ->description('Servicos profissionais')
                ->unitPrice(Money::fromDecimal('1500.00', 'CVE'))
                ->quantity(Quantity::fromDecimal('2.5'))
                ->tax(Percentage::fromBasisPoints(1500))
                ->build(),
        )
        ->addItem(
            ItemBuilder::create()
                ->description('Suporte mensal')
                ->unitPrice(Money::fromDecimal('850.00', 'CVE'))
                ->quantity(Quantity::fromInt(1))
                ->discount(Percentage::fromBasisPoints(500))
                ->tax(Percentage::fromBasisPoints(1500))
                ->build(),
        )
        ->notes('Pagamento a 30 dias.')
        ->build();
};

