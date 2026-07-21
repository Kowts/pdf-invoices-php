<?php

declare(strict_types=1);

use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\Formatting\SimpleCurrencyFormatter;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Localization\ArrayTranslator;
use PdfInvoices\Core\Pdf\BrowsershotEngine;
use PdfInvoices\Core\Storage\LocalStorage;
use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PdfInvoices\Core\Template\NativePhpTemplateRenderer;
use PdfInvoices\Core\Validation\DefaultInvoiceValidator;
use Spatie\Browsershot\Browsershot;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

/** @var callable(): Invoice $makeInvoice */
$makeInvoice = require __DIR__ . '/shared_invoice.php';

if (! class_exists(Browsershot::class)) {
    fwrite(STDERR, "Install Browsershot first: composer require spatie/browsershot\n");
    exit(1);
}

$generator = new InvoiceGenerator(
    new BrowsershotEngine(
        timeoutSeconds: 90,
        noSandbox: false,
    ),
    new NativePhpTemplateRenderer(FilesystemTemplateResolver::default()),
    new InvoiceCalculator(),
    ArrayTranslator::default(),
    new SimpleCurrencyFormatter(),
    new DefaultInvoiceValidator(),
);

$document = $generator->generate($makeInvoice(), 'modern', theme: ['accent' => '#2563eb']);
$path = $document->store(new LocalStorage(dirname(__DIR__, 2) . '/build'), 'invoice-browsershot.pdf');

echo "Generated {$path}\n";
