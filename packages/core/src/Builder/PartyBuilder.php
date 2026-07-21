<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Builder;

use PdfInvoices\Core\Domain\Address;
use PdfInvoices\Core\Domain\Party;

final class PartyBuilder
{
    private ?string $name = null;
    private ?Address $address = null;
    private ?string $taxNumber = null;
    private ?string $email = null;
    private ?string $phone = null;
    private ?string $logoPath = null;

    /** @var array<string, scalar|null> */
    private array $attributes = [];

    public static function create(): self
    {
        return new self();
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function address(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function taxNumber(string $taxNumber): self
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function phone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function logoPath(string $logoPath): self
    {
        $this->logoPath = $logoPath;

        return $this;
    }

    public function set(string $key, string|int|float|bool|null $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function build(): Party
    {
        if ($this->name === null || trim($this->name) === '') {
            throw new \InvalidArgumentException('Party name is required.');
        }

        return new Party(
            name: $this->name,
            address: $this->address,
            taxNumber: $this->taxNumber,
            email: $this->email,
            phone: $this->phone,
            logoPath: $this->logoPath,
            attributes: $this->attributes,
        );
    }
}
