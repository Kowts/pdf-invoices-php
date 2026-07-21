<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Support;

final class Escaper
{
    public static function html(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

