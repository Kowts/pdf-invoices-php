<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Pdf;

use Dompdf\Dompdf;
use Dompdf\Options;
use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Exception\InvoiceException;

final class DompdfEngine implements PdfEngineInterface
{
    public function render(string $html, PdfOptions $options): GeneratedDocument
    {
        if (! class_exists(Dompdf::class)) {
            throw new InvoiceException('Dompdf is not installed. Run composer require dompdf/dompdf.');
        }

        $dompdfOptions = new Options();
        $dompdfOptions->setIsRemoteEnabled($options->allowRemoteResources);
        $dompdfOptions->setIsJavascriptEnabled(false);
        $dompdfOptions->setChroot(getcwd() ?: __DIR__);

        $dompdf = new Dompdf($dompdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($options->format, $options->orientation);
        $dompdf->render();

        return new GeneratedDocument($dompdf->output());
    }
}

