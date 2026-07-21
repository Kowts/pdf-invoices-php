<?php

declare(strict_types=1);

namespace PdfInvoices\Core\ValueObject;

use InvalidArgumentException;

final readonly class Percentage
{
    public function __construct(private int $basisPoints)
    {
        if ($basisPoints < 0) {
            throw new InvalidArgumentException('Percentage cannot be negative.');
        }
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public static function fromBasisPoints(int $basisPoints): self
    {
        return new self($basisPoints);
    }

    public static function fromDecimal(string $decimal): self
    {
        if (! preg_match('/^\d+(?:\.\d{1,4})?$/', $decimal)) {
            throw new InvalidArgumentException('Percentage must be a decimal string.');
        }

        [$whole, $fraction] = array_pad(explode('.', $decimal, 2), 2, '');
        $fraction = substr(str_pad($fraction, 4, '0'), 0, 4);

        return new self(((int) $whole) * 10000 + (int) $fraction);
    }

    public function basisPoints(): int
    {
        return $this->basisPoints;
    }

    public function applyTo(Money $money): Money
    {
        return $money->multiplyRatio($this->basisPoints, 10000);
    }
}

