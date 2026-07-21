<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Builder;

use DateTimeImmutable;
use DateTimeInterface;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\Domain\InvoiceItem;
use PdfInvoices\Core\Domain\Party;
use PdfInvoices\Core\ValueObject\Percentage;

final class InvoiceBuilder
{
    private ?Party $seller = null;
    private ?Party $buyer = null;

    /** @var list<InvoiceItem> */
    private array $items = [];
    private string $currency = 'EUR';
    private ?string $number = null;
    private ?DateTimeImmutable $issuedAt = null;
    private ?DateTimeImmutable $dueAt = null;
    private ?string $locale = null;
    private ?string $notes = null;
    private Percentage $globalDiscount;

    /** @var list<Percentage> */
    private array $withholdings = [];

    /** @var array<string, scalar|null> */
    private array $attributes = [];

    public function __construct()
    {
        $this->globalDiscount = Percentage::zero();
    }

    public static function create(): self
    {
        return new self();
    }

    public function seller(Party $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function buyer(Party $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function addItem(InvoiceItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function currency(string $currency): self
    {
        $this->currency = strtoupper($currency);

        return $this;
    }

    public function number(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function issuedAt(DateTimeInterface $date): self
    {
        $this->issuedAt = DateTimeImmutable::createFromInterface($date);

        return $this;
    }

    public function dueAt(DateTimeInterface $date): self
    {
        $this->dueAt = DateTimeImmutable::createFromInterface($date);

        return $this;
    }

    public function locale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function notes(string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function globalDiscount(Percentage $discount): self
    {
        $this->globalDiscount = $discount;

        return $this;
    }

    public function withholding(Percentage $rate): self
    {
        $this->withholdings[] = $rate;

        return $this;
    }

    public function set(string $key, string|int|float|bool|null $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function build(): Invoice
    {
        if (! $this->seller instanceof Party) {
            throw new \InvalidArgumentException('Seller is required.');
        }

        if (! $this->buyer instanceof Party) {
            throw new \InvalidArgumentException('Buyer is required.');
        }

        return new Invoice(
            seller: $this->seller,
            buyer: $this->buyer,
            items: $this->items,
            currency: $this->currency,
            number: $this->number,
            issuedAt: $this->issuedAt,
            dueAt: $this->dueAt,
            locale: $this->locale,
            notes: $this->notes,
            globalDiscount: $this->globalDiscount,
            withholdings: $this->withholdings,
            attributes: $this->attributes,
        );
    }
}
