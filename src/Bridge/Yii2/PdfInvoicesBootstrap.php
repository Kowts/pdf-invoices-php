<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Bridge\Yii2;

use yii\base\Application;
use yii\base\BootstrapInterface;

final class PdfInvoicesBootstrap implements BootstrapInterface
{
    /**
     * @param mixed $app
     */
    public function bootstrap($app): void
    {
        if ($app instanceof Application && ! $app->has('pdfInvoices')) {
            $app->set('pdfInvoices', PdfInvoicesComponent::class);
        }
    }
}
