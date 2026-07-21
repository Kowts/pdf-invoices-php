<?php

declare(strict_types=1);

use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\Formatting\SimpleCurrencyFormatter;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Localization\ArrayTranslator;
use PdfInvoices\Core\Pdf\HtmlPreviewEngine;
use PdfInvoices\Core\Storage\LocalStorage;
use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PdfInvoices\Core\Template\NativePhpTemplateRenderer;
use PdfInvoices\Core\Validation\DefaultInvoiceValidator;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

/** @var callable(): Invoice $makeInvoice */
$makeInvoice = require __DIR__ . '/shared_invoice.php';

$generator = new InvoiceGenerator(
    new HtmlPreviewEngine(),
    new NativePhpTemplateRenderer(
        FilesystemTemplateResolver::withDefaultTemplates(__DIR__ . '/templates'),
    ),
    new InvoiceCalculator(),
    ArrayTranslator::default(),
    new SimpleCurrencyFormatter(),
    new DefaultInvoiceValidator(),
);

$document = $generator->generate(
    $makeInvoice(),
    'compact-brand',
    theme: [
        'accent' => '#7c3aed',
        'brand' => 'Kowts Studio',
        'footer' => 'Documento gerado com template customizado.',
    ],
);

$path = $document->store(new LocalStorage(dirname(__DIR__, 2) . '/build'), 'custom-template.html');

echo "Generated {$path}\n";
