<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\ValueObject;

use InvalidArgumentException;
use PdfInvoices\Core\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testItCreatesMoneyFromDecimalString(): void
    {
        $money = Money::fromDecimal('10.235', 'EUR');

        self::assertSame(1024, $money->minorAmount());
        self::assertSame('10.24', $money->toDecimalString());
    }

    public function testItSupportsCurrenciesWithoutFractionDigits(): void
    {
        $money = Money::fromDecimal('1200', 'JPY');

        self::assertSame(1200, $money->minorAmount());
        self::assertSame('1200', $money->toDecimalString());
    }

    public function testItRejectsInvalidCurrencyCodes(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Money::fromMinor(100, 'EURO');
    }
}
