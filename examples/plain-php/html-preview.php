<?php

declare(strict_types=1);

use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Storage\LocalStorage;

/** @var callable(): Invoice $makeInvoice */
$makeInvoice = require __DIR__ . '/shared_invoice.php';

$document = InvoiceGenerator::defaultHtmlPreview()
    ->generate($makeInvoice(), 'modern', theme: ['accent' => '#0f766e']);

$path = $document->store(new LocalStorage(dirname(__DIR__, 2) . '/build'), 'invoice-preview.html');

echo "Generated {$path}\n";

