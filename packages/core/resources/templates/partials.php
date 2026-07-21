<?php

declare(strict_types=1);

use PdfInvoices\Core\Domain\Address;
use PdfInvoices\Core\Domain\Party;
use PdfInvoices\Core\Support\Escaper;

if (! function_exists('pdf_invoices_party_block')) {
    function pdf_invoices_party_block(Party $party): string
    {
        $parts = ['<strong>' . Escaper::html($party->name) . '</strong>'];

        if ($party->taxNumber !== null) {
            $parts[] = Escaper::html($party->taxNumber);
        }

        if ($party->email !== null) {
            $parts[] = Escaper::html($party->email);
        }

        if ($party->phone !== null) {
            $parts[] = Escaper::html($party->phone);
        }

        if ($party->address instanceof Address) {
            foreach ([
                $party->address->line1,
                $party->address->line2,
                trim((string) $party->address->postalCode . ' ' . (string) $party->address->city),
                $party->address->region,
                $party->address->country,
            ] as $line) {
                if ($line !== null && trim($line) !== '') {
                    $parts[] = Escaper::html($line);
                }
            }
        }

        return implode('<br>', $parts);
    }
}

