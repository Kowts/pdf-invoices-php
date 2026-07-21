<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Pdf;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Exception\InvoiceException;

final readonly class MpdfEngine implements PdfEngineInterface
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(private array $config = [])
    {
    }

    public function render(string $html, PdfOptions $options): GeneratedDocument
    {
        if (! class_exists(Mpdf::class)) {
            throw new InvoiceException('mPDF is not installed. Run composer require mpdf/mpdf.');
        }

        $mpdf = new Mpdf([
            'format' => $options->format,
            'orientation' => strtoupper($options->orientation[0] ?? 'P'),
            'margin_top' => $options->marginTopMm,
            'margin_right' => $options->marginRightMm,
            'margin_bottom' => $options->marginBottomMm,
            'margin_left' => $options->marginLeftMm,
        ] + $this->config);

        $mpdf->WriteHTML($html);

        $contents = $mpdf->Output('', Destination::STRING_RETURN);
        if (! is_string($contents)) {
            throw new InvoiceException('mPDF did not return PDF contents.');
        }

        return new GeneratedDocument($contents);
    }
}
