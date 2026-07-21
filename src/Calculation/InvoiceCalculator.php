<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Calculation;

use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\Domain\InvoiceItem;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;

final class InvoiceCalculator
{
    public function calculate(Invoice $invoice): InvoiceTotals
    {
        $zero = Money::zero($invoice->currency);
        $lines = [];
        $subtotal = $zero;
        $lineDiscounts = $zero;
        $taxableBase = $zero;
        $tax = $zero;

        foreach ($invoice->items as $item) {
            $line = $this->calculateLine($item);
            $lines[] = $line;
            $subtotal = $subtotal->add($line->subtotal);
            $lineDiscounts = $lineDiscounts->add($line->discount);
            $taxableBase = $taxableBase->add($line->taxableBase);
            $tax = $tax->add($line->tax);
        }

        $globalDiscount = $invoice->globalDiscount->applyTo($taxableBase);
        $taxableBase = $taxableBase->subtract($globalDiscount);

        $withholding = $zero;
        foreach ($invoice->withholdings as $rate) {
            $withholding = $withholding->add($rate->applyTo($taxableBase));
        }

        $total = $taxableBase->add($tax)->subtract($withholding);

        return new InvoiceTotals(
            lines: $lines,
            subtotal: $subtotal,
            lineDiscounts: $lineDiscounts,
            globalDiscount: $globalDiscount,
            taxableBase: $taxableBase,
            tax: $tax,
            withholding: $withholding,
            total: $total,
        );
    }

    public function calculateLine(InvoiceItem $item): LineTotals
    {
        $subtotal = $item->quantity->multiply($item->unitPrice);
        $discount = $item->discount->applyTo($subtotal);
        $afterDiscount = $subtotal->subtract($discount);

        $combinedTaxRate = 0;
        foreach ($item->taxes as $rate) {
            $combinedTaxRate += $rate->basisPoints();
        }

        $tax = $this->calculateTax($afterDiscount, Percentage::fromBasisPoints($combinedTaxRate), $item->taxIncluded);

        $taxableBase = $item->taxIncluded ? $afterDiscount->subtract($tax) : $afterDiscount;
        $total = $item->taxIncluded ? $afterDiscount : $afterDiscount->add($tax);

        return new LineTotals($subtotal, $discount, $taxableBase, $tax, $total);
    }

    private function calculateTax(Money $amount, Percentage $rate, bool $included): Money
    {
        if (! $included) {
            return $rate->applyTo($amount);
        }

        $basisPoints = $rate->basisPoints();

        return $amount->multiplyRatio($basisPoints, 10000 + $basisPoints);
    }
}
