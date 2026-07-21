<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Contract;

interface TemplateResolverInterface
{
    public function resolve(string $template): string;
}
