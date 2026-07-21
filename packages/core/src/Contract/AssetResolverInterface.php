<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Contract;

interface AssetResolverInterface
{
    public function resolve(string $pathOrUrl): string;
}

