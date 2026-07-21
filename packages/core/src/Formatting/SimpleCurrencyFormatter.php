<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Formatting;

use PdfInvoices\Core\Contract\CurrencyFormatterInterface;
use PdfInvoices\Core\ValueObject\Money;

final class SimpleCurrencyFormatter implements CurrencyFormatterInterface
{
    public function format(Money $money, ?string $locale = null): string
    {
        $amount = $money->toDecimalString();
        $separator = str_starts_with((string) $locale, 'pt') ? ' ' : ' ';

        return $amount . $separator . $money->currency();
    }
}
