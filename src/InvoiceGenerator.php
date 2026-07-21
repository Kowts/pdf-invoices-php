<?php

declare(strict_types=1);

namespace PdfInvoices\Core;

use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\Contract\CurrencyFormatterInterface;
use PdfInvoices\Core\Contract\InvoiceValidatorInterface;
use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Contract\TemplateRendererInterface;
use PdfInvoices\Core\Contract\TranslatorInterface;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\Exception\ValidationException;
use PdfInvoices\Core\Formatting\SimpleCurrencyFormatter;
use PdfInvoices\Core\Localization\ArrayTranslator;
use PdfInvoices\Core\Pdf\GeneratedDocument;
use PdfInvoices\Core\Pdf\HtmlPreviewEngine;
use PdfInvoices\Core\Pdf\PdfOptions;
use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PdfInvoices\Core\Template\NativePhpTemplateRenderer;
use PdfInvoices\Core\Template\TemplateContext;
use PdfInvoices\Core\Validation\DefaultInvoiceValidator;

final readonly class InvoiceGenerator
{
    public function __construct(
        private PdfEngineInterface $pdfEngine,
        private TemplateRendererInterface $renderer,
        private InvoiceCalculator $calculator,
        private TranslatorInterface $translator,
        private CurrencyFormatterInterface $currencyFormatter,
        private InvoiceValidatorInterface $validator,
    ) {
    }

    public static function defaultHtmlPreview(): self
    {
        return new self(
            new HtmlPreviewEngine(),
            new NativePhpTemplateRenderer(FilesystemTemplateResolver::default()),
            new InvoiceCalculator(),
            ArrayTranslator::default(),
            new SimpleCurrencyFormatter(),
            new DefaultInvoiceValidator(),
        );
    }

    /**
     * @param array<string, scalar|null> $theme
     */
    public function generate(
        Invoice $invoice,
        string $template = 'modern',
        ?PdfOptions $options = null,
        array $theme = [],
    ): GeneratedDocument {
        $errors = $this->validator->validate($invoice);
        if ($errors !== []) {
            throw new ValidationException($errors);
        }

        $options ??= new PdfOptions();
        $totals = $this->calculator->calculate($invoice);
        $html = $this->renderer->render(
            $template,
            new TemplateContext($invoice, $totals, $this->translator, $this->currencyFormatter, $options, $theme),
        );

        return $this->pdfEngine->render($html, $options);
    }
}
