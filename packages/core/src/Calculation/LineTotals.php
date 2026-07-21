<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Calculation;

use PdfInvoices\Core\ValueObject\Money;

final readonly class LineTotals
{
    public function __construct(
        public Money $subtotal,
        public Money $discount,
        public Money $taxableBase,
        public Money $tax,
        public Money $total,
    ) {
    }
}

