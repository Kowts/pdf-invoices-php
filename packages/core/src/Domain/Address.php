<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Domain;

final readonly class Address
{
    public function __construct(
        public ?string $line1 = null,
        public ?string $line2 = null,
        public ?string $postalCode = null,
        public ?string $city = null,
        public ?string $region = null,
        public ?string $country = null,
    ) {
    }
}
