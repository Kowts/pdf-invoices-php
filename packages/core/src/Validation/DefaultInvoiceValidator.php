<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Validation;

use PdfInvoices\Core\Contract\InvoiceValidatorInterface;
use PdfInvoices\Core\Domain\Invoice;

final class DefaultInvoiceValidator implements InvoiceValidatorInterface
{
    public function validate(Invoice $invoice): array
    {
        $errors = [];

        if (trim($invoice->seller->name) === '') {
            $errors[] = 'Seller name is required.';
        }

        if (trim($invoice->buyer->name) === '') {
            $errors[] = 'Buyer name is required.';
        }

        if ($invoice->items === []) {
            $errors[] = 'At least one item is required.';
        }

        foreach ($invoice->items as $index => $item) {
            if ($item->unitPrice->currency() !== $invoice->currency) {
                $errors[] = sprintf('Item %d currency does not match invoice currency.', $index + 1);
            }

            if ($item->unitPrice->isNegative() && ! (bool) ($invoice->get('credit_note', false))) {
                $errors[] = sprintf('Item %d has a negative price outside a credit note.', $index + 1);
            }
        }

        return $errors;
    }
}

