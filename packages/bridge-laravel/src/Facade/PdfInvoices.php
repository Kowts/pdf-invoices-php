<?php

declare(strict_types=1);

namespace PdfInvoices\Laravel\Facade;

use Illuminate\Support\Facades\Facade;
use PdfInvoices\Core\InvoiceGenerator;

final class PdfInvoices extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InvoiceGenerator::class;
    }
}

