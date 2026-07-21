<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Domain;

use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;

final readonly class InvoiceItem
{
    /**
     * @param list<Percentage> $taxes
     * @param array<string, scalar|null> $attributes
     */
    public function __construct(
        public string $description,
        public Money $unitPrice,
        public Quantity $quantity,
        public Percentage $discount,
        public array $taxes = [],
        public bool $taxIncluded = false,
        public array $attributes = [],
    ) {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }
}
