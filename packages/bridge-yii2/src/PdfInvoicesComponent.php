<?php

declare(strict_types=1);

namespace PdfInvoices\Yii2;

use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Domain\Invoice;
use PdfInvoices\Core\Formatting\SimpleCurrencyFormatter;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Pdf\DompdfEngine;
use PdfInvoices\Core\Pdf\GeneratedDocument;
use PdfInvoices\Core\Pdf\PdfOptions;
use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PdfInvoices\Core\Template\NativePhpTemplateRenderer;
use PdfInvoices\Core\Validation\DefaultInvoiceValidator;
use PdfInvoices\Yii2\Translation\YiiTranslator;
use Yii;
use yii\base\Component;

final class PdfInvoicesComponent extends Component
{
    public string $template = 'modern';

    public string $locale = 'pt_PT';

    /** @var class-string<PdfEngineInterface> */
    public string $engine = DompdfEngine::class;

    public function generate(Invoice $invoice, ?string $template = null, ?PdfOptions $options = null): GeneratedDocument
    {
        $generator = new InvoiceGenerator(
            Yii::createObject($this->engine),
            new NativePhpTemplateRenderer(FilesystemTemplateResolver::default()),
            new InvoiceCalculator(),
            new YiiTranslator(),
            new SimpleCurrencyFormatter(),
            new DefaultInvoiceValidator(),
        );

        return $generator->generate($invoice, $template ?? $this->template, $options);
    }
}
