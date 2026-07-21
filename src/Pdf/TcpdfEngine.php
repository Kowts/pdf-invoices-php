<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Pdf;

use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Exception\InvoiceException;
use TCPDF;

final readonly class TcpdfEngine implements PdfEngineInterface
{
    public function __construct(
        private bool $printHeader = false,
        private bool $printFooter = false,
    ) {
    }

    public function render(string $html, PdfOptions $options): GeneratedDocument
    {
        if (! class_exists(TCPDF::class)) {
            throw new InvoiceException('TCPDF is not installed. Run composer require tecnickcom/tcpdf.');
        }

        $pdf = new TCPDF(
            strtoupper($options->orientation[0] ?? 'P'),
            'mm',
            $options->format,
            true,
            'UTF-8',
            false,
        );

        $pdf->setPrintHeader($this->printHeader);
        $pdf->setPrintFooter($this->printFooter);
        $pdf->SetMargins($options->marginLeftMm, $options->marginTopMm, $options->marginRightMm);
        $pdf->SetAutoPageBreak(true, $options->marginBottomMm);
        $pdf->AddPage();
        $pdf->writeHTML($html);

        $contents = $pdf->Output('', 'S');
        if (! is_string($contents)) {
            throw new InvoiceException('TCPDF did not return PDF contents.');
        }

        return new GeneratedDocument($contents);
    }
}
