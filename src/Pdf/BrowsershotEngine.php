<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Pdf;

use Closure;
use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Exception\InvoiceException;
use Spatie\Browsershot\Browsershot;

final readonly class BrowsershotEngine implements PdfEngineInterface
{
    public function __construct(
        private ?string $nodeBinary = null,
        private ?string $npmBinary = null,
        private ?string $chromePath = null,
        private int $timeoutSeconds = 60,
        private bool $noSandbox = false,
        private bool $disableJavascript = true,
        private ?Closure $browserFactory = null,
    ) {
    }

    public function render(string $html, PdfOptions $options): GeneratedDocument
    {
        if (! class_exists(Browsershot::class)) {
            throw new InvoiceException('Browsershot is not installed. Run composer require spatie/browsershot.');
        }

        $browser = $this->browserFactory instanceof Closure
            ? ($this->browserFactory)($html)
            : Browsershot::html($html);

        $browser
            ->format($options->format)
            ->margins(
                $options->marginTopMm,
                $options->marginRightMm,
                $options->marginBottomMm,
                $options->marginLeftMm,
            )
            ->showBackground()
            ->timeout($this->timeoutSeconds);

        if (strtolower($options->orientation) === 'landscape') {
            $browser->landscape();
        }

        if (! $options->allowRemoteResources) {
            $browser->blockUrls(['http://*', 'https://*']);
        }

        if ($this->disableJavascript) {
            $browser->disableJavascript();
        }

        if ($this->nodeBinary !== null) {
            $browser->setNodeBinary($this->nodeBinary);
        }

        if ($this->npmBinary !== null) {
            $browser->setNpmBinary($this->npmBinary);
        }

        if ($this->chromePath !== null) {
            $browser->setChromePath($this->chromePath);
        }

        if ($this->noSandbox) {
            $browser->noSandbox();
        }

        return new GeneratedDocument($browser->pdf());
    }
}
