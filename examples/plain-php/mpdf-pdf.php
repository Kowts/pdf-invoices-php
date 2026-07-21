<?php

declare(strict_types=1);

use Mpdf\Mpdf;
use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\Formatting\SimpleCurrencyFormatter;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Localization\ArrayTranslator;
use PdfInvoices\Core\Pdf\MpdfEngine;
use PdfInvoices\Core\Storage\LocalStorage;
use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PdfInvoices\Core\Template\NativePhpTemplateRenderer;
use PdfInvoices\Core\Validation\DefaultInvoiceValidator;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

/** @var callable(): Invoice $makeInvoice */
$makeInvoice = require __DIR__ . '/shared_invoice.php';

if (! class_exists(Mpdf::class)) {
    fwrite(STDERR, "Install mPDF first: composer require mpdf/mpdf\n");
    exit(1);
}

$generator = new InvoiceGenerator(
    new MpdfEngine(),
    new NativePhpTemplateRenderer(FilesystemTemplateResolver::default()),
    new InvoiceCalculator(),
    ArrayTranslator::default(),
    new SimpleCurrencyFormatter(),
    new DefaultInvoiceValidator(),
);

$document = $generator->generate($makeInvoice(), 'branded', theme: ['accent' => '#7c3aed']);
$path = $document->store(new LocalStorage(dirname(__DIR__, 2) . '/build'), 'invoice-mpdf.pdf');

echo "Generated {$path}\n";
