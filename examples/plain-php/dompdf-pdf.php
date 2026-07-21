<?php

declare(strict_types=1);

use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\Formatting\SimpleCurrencyFormatter;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Localization\ArrayTranslator;
use PdfInvoices\Core\Pdf\DompdfEngine;
use PdfInvoices\Core\Storage\LocalStorage;
use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PdfInvoices\Core\Template\NativePhpTemplateRenderer;
use PdfInvoices\Core\Validation\DefaultInvoiceValidator;

/** @var callable(): Invoice $makeInvoice */
$makeInvoice = require __DIR__ . '/shared_invoice.php';

if (! class_exists(\Dompdf\Dompdf::class)) {
    fwrite(STDERR, "Install Dompdf first: composer require dompdf/dompdf\n");
    exit(1);
}

$generator = new InvoiceGenerator(
    new DompdfEngine(),
    new NativePhpTemplateRenderer(FilesystemTemplateResolver::default()),
    new InvoiceCalculator(),
    ArrayTranslator::default(),
    new SimpleCurrencyFormatter(),
    new DefaultInvoiceValidator(),
);

$document = $generator->generate($makeInvoice(), 'branded', theme: ['accent' => '#1d4ed8']);
$path = $document->store(new LocalStorage(dirname(__DIR__, 2) . '/build'), 'invoice.pdf');

echo "Generated {$path}\n";

