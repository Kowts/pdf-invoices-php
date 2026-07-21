<?php

declare(strict_types=1);

namespace PdfInvoices\Core\ValueObject;

use InvalidArgumentException;

final readonly class Money
{
    private const DEFAULT_FRACTION_DIGITS = [
        'BIF' => 0,
        'CVE' => 2,
        'DJF' => 0,
        'EUR' => 2,
        'GBP' => 2,
        'GNF' => 0,
        'JPY' => 0,
        'KMF' => 0,
        'KRW' => 0,
        'MGA' => 2,
        'PYG' => 0,
        'RWF' => 0,
        'USD' => 2,
        'VND' => 0,
        'VUV' => 0,
        'XAF' => 0,
        'XOF' => 0,
        'XPF' => 0,
    ];

    private string $currency;

    private int $fractionDigits;

    public function __construct(private int $minorAmount, string $currency, int $fractionDigits = 2)
    {
        $currency = strtoupper($currency);

        if (! preg_match('/^[A-Z]{3}$/', $currency)) {
            throw new InvalidArgumentException('Currency must be an ISO 4217 code.');
        }

        if ($fractionDigits < 0 || $fractionDigits > 6) {
            throw new InvalidArgumentException('Fraction digits must be between 0 and 6.');
        }

        $this->currency = $currency;
        $this->fractionDigits = $fractionDigits;
    }

    public static function zero(string $currency, ?int $fractionDigits = null): self
    {
        return new self(0, $currency, $fractionDigits ?? self::fractionDigitsFor($currency));
    }

    public static function fromMinor(int $minorAmount, string $currency, ?int $fractionDigits = null): self
    {
        return new self($minorAmount, $currency, $fractionDigits ?? self::fractionDigitsFor($currency));
    }

    public static function fromDecimal(string $amount, string $currency, ?int $fractionDigits = null): self
    {
        $scale = $fractionDigits ?? self::fractionDigitsFor($currency);

        if (! preg_match('/^-?\d+(?:\.\d+)?$/', $amount)) {
            throw new InvalidArgumentException('Money amount must be a decimal string.');
        }

        $negative = str_starts_with($amount, '-');
        $normalized = ltrim($amount, '-');
        [$whole, $fraction] = array_pad(explode('.', $normalized, 2), 2, '');
        $fraction = substr(str_pad($fraction, $scale + 1, '0'), 0, $scale + 1);
        $minor = ((int) $whole) * (10 ** $scale) + (int) substr($fraction, 0, $scale);

        if ($scale >= 0 && strlen($fraction) > $scale && (int) $fraction[$scale] >= 5) {
            $minor++;
        }

        return new self($negative ? -$minor : $minor, $currency, $scale);
    }

    public static function fractionDigitsFor(string $currency): int
    {
        return self::DEFAULT_FRACTION_DIGITS[strtoupper($currency)] ?? 2;
    }

    public function minorAmount(): int
    {
        return $this->minorAmount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function fractionDigits(): int
    {
        return $this->fractionDigits;
    }

    public function add(self $money): self
    {
        $this->assertSameCurrency($money);

        return new self($this->minorAmount + $money->minorAmount, $this->currency, $this->fractionDigits);
    }

    public function subtract(self $money): self
    {
        $this->assertSameCurrency($money);

        return new self($this->minorAmount - $money->minorAmount, $this->currency, $this->fractionDigits);
    }

    public function multiplyRatio(int $numerator, int $denominator): self
    {
        if ($denominator <= 0) {
            throw new InvalidArgumentException('Denominator must be positive.');
        }

        $value = intdiv(abs($this->minorAmount) * $numerator + intdiv($denominator, 2), $denominator);

        return new self($this->minorAmount < 0 ? -$value : $value, $this->currency, $this->fractionDigits);
    }

    public function isNegative(): bool
    {
        return $this->minorAmount < 0;
    }

    public function toDecimalString(): string
    {
        if ($this->fractionDigits === 0) {
            return (string) $this->minorAmount;
        }

        $negative = $this->minorAmount < 0 ? '-' : '';
        $absolute = abs($this->minorAmount);
        $factor = 10 ** $this->fractionDigits;
        $whole = intdiv($absolute, $factor);
        $fraction = str_pad((string) ($absolute % $factor), $this->fractionDigits, '0', STR_PAD_LEFT);

        return $negative . $whole . '.' . $fraction;
    }

    private function assertSameCurrency(self $money): void
    {
        if ($this->currency !== $money->currency || $this->fractionDigits !== $money->fractionDigits) {
            throw new InvalidArgumentException('Money values must use the same currency and scale.');
        }
    }
}
