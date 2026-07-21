<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Contract;

use PdfInvoices\Core\Template\TemplateContext;

interface TemplateRendererInterface
{
    public function render(string $template, TemplateContext $context): string;
}

