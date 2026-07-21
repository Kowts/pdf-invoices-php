<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Domain;

final readonly class Party
{
    /**
     * @param array<string, scalar|null> $attributes
     */
    public function __construct(
        public string $name,
        public ?Address $address = null,
        public ?string $taxNumber = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $logoPath = null,
        public array $attributes = [],
    ) {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }
}
