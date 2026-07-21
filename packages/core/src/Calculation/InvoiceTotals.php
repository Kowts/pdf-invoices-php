<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Calculation;

use PdfInvoices\Core\ValueObject\Money;

final readonly class InvoiceTotals
{
    /**
     * @param list<LineTotals> $lines
     */
    public function __construct(
        public array $lines,
        public Money $subtotal,
        public Money $lineDiscounts,
        public Money $globalDiscount,
        public Money $taxableBase,
        public Money $tax,
        public Money $withholding,
        public Money $total,
    ) {
    }
}

