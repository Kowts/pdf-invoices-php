<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Calculation;

use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;
use PHPUnit\Framework\TestCase;

final class InvoiceCalculatorTest extends TestCase
{
    public function testItCalculatesDiscountTaxAndTotalWithoutFloats(): void
    {
        $invoice = InvoiceBuilder::create()
            ->seller(PartyBuilder::create()->name('Seller')->build())
            ->buyer(PartyBuilder::create()->name('Buyer')->build())
            ->currency('EUR')
            ->addItem(
                ItemBuilder::create()
                    ->description('Services')
                    ->unitPrice(Money::fromMinor(10000, 'EUR'))
                    ->quantity(Quantity::fromInt(2))
                    ->discount(Percentage::fromBasisPoints(1000))
                    ->tax(Percentage::fromBasisPoints(2300))
                    ->build(),
            )
            ->build();

        $totals = (new InvoiceCalculator())->calculate($invoice);

        self::assertSame(20000, $totals->subtotal->minorAmount());
        self::assertSame(2000, $totals->lineDiscounts->minorAmount());
        self::assertSame(18000, $totals->taxableBase->minorAmount());
        self::assertSame(4140, $totals->tax->minorAmount());
        self::assertSame(22140, $totals->total->minorAmount());
    }

    public function testItSupportsFractionalQuantities(): void
    {
        $invoice = InvoiceBuilder::create()
            ->seller(PartyBuilder::create()->name('Seller')->build())
            ->buyer(PartyBuilder::create()->name('Buyer')->build())
            ->currency('EUR')
            ->addItem(
                ItemBuilder::create()
                    ->description('Hours')
                    ->unitPrice(Money::fromMinor(2500, 'EUR'))
                    ->quantity(Quantity::fromDecimal('1.5'))
                    ->build(),
            )
            ->build();

        $totals = (new InvoiceCalculator())->calculate($invoice);

        self::assertSame(3750, $totals->total->minorAmount());
    }
}

