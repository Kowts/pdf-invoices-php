<?php

declare(strict_types=1);

use PdfInvoices\Core\Pdf\DompdfEngine;

return [
    'template' => 'modern',
    'locale' => env('PDF_INVOICES_LOCALE', 'pt_PT'),
    'storage_path' => storage_path('app/invoices'),
    'engine' => DompdfEngine::class,
    'remote_resources' => false,
];
