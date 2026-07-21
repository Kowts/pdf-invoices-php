<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Bridge\Yii2\Http;

use PdfInvoices\Core\Pdf\GeneratedDocument;
use Yii;
use yii\web\Response;

final class InvoiceResponseFactory
{
    public function download(GeneratedDocument $document, string $filename): Response
    {
        return Yii::$app->response->sendContentAsFile(
            $document->contents(),
            $filename,
            ['mimeType' => $document->mimeType(), 'inline' => false],
        );
    }
}
