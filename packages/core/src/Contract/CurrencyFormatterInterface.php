<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Contract;

use PdfInvoices\Core\ValueObject\Money;

interface CurrencyFormatterInterface
{
    public function format(Money $money, ?string $locale = null): string;
}

