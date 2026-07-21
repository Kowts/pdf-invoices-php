<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Builder;

use PdfInvoices\Core\Domain\InvoiceItem;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;

final class ItemBuilder
{
    private ?string $description = null;
    private ?Money $unitPrice = null;
    private Quantity $quantity;
    private Percentage $discount;

    /** @var list<Percentage> */
    private array $taxes = [];
    private bool $taxIncluded = false;

    /** @var array<string, scalar|null> */
    private array $attributes = [];

    public function __construct()
    {
        $this->quantity = Quantity::one();
        $this->discount = Percentage::zero();
    }

    public static function create(): self
    {
        return new self();
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function unitPrice(Money $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function quantity(Quantity $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function discount(Percentage $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function tax(Percentage $tax): self
    {
        $this->taxes[] = $tax;

        return $this;
    }

    public function taxIncluded(bool $taxIncluded = true): self
    {
        $this->taxIncluded = $taxIncluded;

        return $this;
    }

    public function set(string $key, string|int|float|bool|null $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function build(): InvoiceItem
    {
        if ($this->description === null || trim($this->description) === '') {
            throw new \InvalidArgumentException('Item description is required.');
        }

        if (! $this->unitPrice instanceof Money) {
            throw new \InvalidArgumentException('Item unit price is required.');
        }

        return new InvoiceItem(
            description: $this->description,
            unitPrice: $this->unitPrice,
            quantity: $this->quantity,
            discount: $this->discount,
            taxes: $this->taxes,
            taxIncluded: $this->taxIncluded,
            attributes: $this->attributes,
        );
    }
}

