<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Domain;

use DateTimeImmutable;
use PdfInvoices\Core\ValueObject\Percentage;

final readonly class Invoice
{
    /**
     * @param list<InvoiceItem> $items
     * @param list<Percentage> $withholdings
     * @param array<string, scalar|null> $attributes
     */
    public function __construct(
        public Party $seller,
        public Party $buyer,
        public array $items,
        public string $currency,
        public ?string $number = null,
        public ?DateTimeImmutable $issuedAt = null,
        public ?DateTimeImmutable $dueAt = null,
        public ?string $locale = null,
        public ?string $notes = null,
        public Percentage $globalDiscount = new Percentage(0),
        public array $withholdings = [],
        public array $attributes = [],
    ) {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }
}

