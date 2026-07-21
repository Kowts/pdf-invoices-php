<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Template;

use PdfInvoices\Core\Contract\TemplateRendererInterface;
use PdfInvoices\Core\Contract\TemplateResolverInterface;

final readonly class NativePhpTemplateRenderer implements TemplateRendererInterface
{
    public function __construct(private TemplateResolverInterface $resolver)
    {
    }

    public function render(string $template, TemplateContext $context): string
    {
        $file = $this->resolver->resolve($template);

        ob_start();
        $invoice = $context->invoice;
        $totals = $context->totals;
        $t = $context->translator;
        $money = $context->currencyFormatter;
        $theme = $context->theme;

        include $file;

        return (string) ob_get_clean();
    }
}

