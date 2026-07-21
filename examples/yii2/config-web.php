<?php

declare(strict_types=1);

use PdfInvoices\Core\Bridge\Yii2\PdfInvoicesComponent;

return [
    'components' => [
        'pdfInvoices' => [
            'class' => PdfInvoicesComponent::class,
            'template' => 'modern',
            'locale' => 'pt_PT',
        ],
    ],
];

