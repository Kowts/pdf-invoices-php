<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Contract;

use PdfInvoices\Core\Domain\Invoice;

interface InvoiceValidatorInterface
{
    /**
     * @return list<string>
     */
    public function validate(Invoice $invoice): array;
}
