<?php

declare(strict_types=1);

namespace PdfInvoices\Core\ValueObject;

use InvalidArgumentException;

final readonly class Quantity
{
    private const SCALE = 1000;

    public function __construct(private int $units)
    {
        if ($units < 0) {
            throw new InvalidArgumentException('Quantity cannot be negative.');
        }
    }

    public static function one(): self
    {
        return new self(self::SCALE);
    }

    public static function fromInt(int $quantity): self
    {
        return new self($quantity * self::SCALE);
    }

    public static function fromDecimal(string $quantity): self
    {
        if (! preg_match('/^\d+(?:\.\d{1,3})?$/', $quantity)) {
            throw new InvalidArgumentException('Quantity must be a decimal string with up to 3 decimal places.');
        }

        [$whole, $fraction] = array_pad(explode('.', $quantity, 2), 2, '');
        $fraction = substr(str_pad($fraction, 3, '0'), 0, 3);

        return new self(((int) $whole) * self::SCALE + (int) $fraction);
    }

    public function units(): int
    {
        return $this->units;
    }

    public function multiply(Money $money): Money
    {
        return $money->multiplyRatio($this->units, self::SCALE);
    }

    public function toDecimalString(): string
    {
        $whole = intdiv($this->units, self::SCALE);
        $fraction = $this->units % self::SCALE;

        if ($fraction === 0) {
            return (string) $whole;
        }

        return $whole . '.' . rtrim(str_pad((string) $fraction, 3, '0', STR_PAD_LEFT), '0');
    }
}

