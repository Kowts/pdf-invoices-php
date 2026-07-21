<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Pdf;

use Dompdf\Dompdf;
use Mpdf\Mpdf;
use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Pdf\BrowsershotEngine;
use PdfInvoices\Core\Pdf\DompdfEngine;
use PdfInvoices\Core\Pdf\HtmlPreviewEngine;
use PdfInvoices\Core\Pdf\MpdfEngine;
use PdfInvoices\Core\Pdf\PdfOptions;
use PdfInvoices\Core\Pdf\TcpdfEngine;
use PHPUnit\Framework\TestCase;
use Spatie\Browsershot\Browsershot;
use TCPDF;

final class PdfEnginesTest extends TestCase
{
    public function testHtmlPreviewEngineRendersHtmlContents(): void
    {
        $document = (new HtmlPreviewEngine())->render('<h1>Invoice</h1>', new PdfOptions());

        self::assertSame('text/html', $document->mimeType());
        self::assertSame('<h1>Invoice</h1>', $document->contents());
    }

    public function testDompdfEngineRendersPdfContents(): void
    {
        if (! class_exists(Dompdf::class)) {
            self::markTestSkipped('Dompdf is not installed.');
        }

        $document = (new DompdfEngine())->render('<h1>Invoice</h1>', new PdfOptions(
            format: 'A5',
            orientation: 'landscape',
            marginTopMm: 8,
            marginRightMm: 9,
            marginBottomMm: 10,
            marginLeftMm: 11,
        ));

        self::assertSame('application/pdf', $document->mimeType());
        self::assertStringStartsWith('%PDF-', $document->contents());
    }

    public function testMpdfEngineRendersPdfContents(): void
    {
        if (! class_exists(Mpdf::class)) {
            self::markTestSkipped('mPDF is not installed.');
        }

        $document = (new MpdfEngine())->render('<h1>Invoice</h1>', new PdfOptions(
            format: 'A5',
            orientation: 'landscape',
            marginTopMm: 8,
            marginRightMm: 9,
            marginBottomMm: 10,
            marginLeftMm: 11,
        ));

        self::assertSame('application/pdf', $document->mimeType());
        self::assertStringStartsWith('%PDF-', $document->contents());
    }

    public function testTcpdfEngineRendersPdfContents(): void
    {
        if (! class_exists(TCPDF::class)) {
            self::markTestSkipped('TCPDF is not installed.');
        }

        $document = (new TcpdfEngine())->render('<h1>Invoice</h1>', new PdfOptions(
            format: 'A5',
            orientation: 'landscape',
            marginTopMm: 8,
            marginRightMm: 9,
            marginBottomMm: 10,
            marginLeftMm: 11,
        ));

        self::assertSame('application/pdf', $document->mimeType());
        self::assertStringStartsWith('%PDF-', $document->contents());
    }

    public function testBrowsershotEngineImplementsPdfEngineContract(): void
    {
        self::assertInstanceOf(PdfEngineInterface::class, new BrowsershotEngine());
    }

    public function testBrowsershotEngineAppliesPdfOptionsWithoutLaunchingChrome(): void
    {
        $fake = new FakeBrowsershot();
        $engine = new BrowsershotEngine(
            nodeBinary: '/usr/bin/node',
            npmBinary: '/usr/bin/npm',
            chromePath: '/usr/bin/chromium',
            timeoutSeconds: 90,
            noSandbox: true,
            browserFactory: static function (string $html) use ($fake): FakeBrowsershot {
                $fake->capturedHtml = $html;

                return $fake;
            },
        );

        $document = $engine->render('<h1>Invoice</h1>', new PdfOptions(
            format: 'A5',
            orientation: 'landscape',
            marginTopMm: 8,
            marginRightMm: 9,
            marginBottomMm: 10,
            marginLeftMm: 11,
            allowRemoteResources: false,
        ));

        self::assertSame('application/pdf', $document->mimeType());
        self::assertStringStartsWith('%PDF-', $document->contents());
        self::assertSame('<h1>Invoice</h1>', $fake->capturedHtml);
        self::assertSame('A5', $fake->capturedFormat);
        self::assertSame([8.0, 9.0, 10.0, 11.0, 'mm'], $fake->capturedMargins);
        self::assertTrue($fake->capturedBackground);
        self::assertSame(90, $fake->capturedTimeout);
        self::assertTrue($fake->capturedLandscape);
        self::assertSame(['http://*', 'https://*'], $fake->capturedBlockedUrls);
        self::assertTrue($fake->capturedJavascriptDisabled);
        self::assertSame('/usr/bin/node', $fake->capturedNodeBinary);
        self::assertSame('/usr/bin/npm', $fake->capturedNpmBinary);
        self::assertSame('/usr/bin/chromium', $fake->capturedChromePath);
        self::assertTrue($fake->capturedSandboxDisabled);
    }
}

final class FakeBrowsershot extends Browsershot
{
    public string $capturedHtml = '';

    public ?string $capturedFormat = null;

    /** @var list<float|int|string>|null */
    public ?array $capturedMargins = null;

    public bool $capturedBackground = false;

    public ?int $capturedTimeout = null;

    public bool $capturedLandscape = false;

    /** @var list<string> */
    public array $capturedBlockedUrls = [];

    public bool $capturedJavascriptDisabled = false;

    public ?string $capturedNodeBinary = null;

    public ?string $capturedNpmBinary = null;

    public ?string $capturedChromePath = null;

    public bool $capturedSandboxDisabled = false;

    public function __construct()
    {
    }

    public function format(string $format): static
    {
        $this->capturedFormat = $format;

        return $this;
    }

    public function margins(float $top, float $right, float $bottom, float $left, string $unit = 'mm'): static
    {
        $this->capturedMargins = [$top, $right, $bottom, $left, $unit];

        return $this;
    }

    public function showBackground(): static
    {
        $this->capturedBackground = true;

        return $this;
    }

    public function timeout(int $timeout): static
    {
        $this->capturedTimeout = $timeout;

        return $this;
    }

    public function landscape(bool $landscape = true): static
    {
        $this->capturedLandscape = $landscape;

        return $this;
    }

    /**
     * @param list<string> $array
     */
    public function blockUrls($array): static
    {
        $this->capturedBlockedUrls = $array;

        return $this;
    }

    public function disableJavascript(): static
    {
        $this->capturedJavascriptDisabled = true;

        return $this;
    }

    public function setNodeBinary(string $nodeBinary): static
    {
        $this->capturedNodeBinary = $nodeBinary;

        return $this;
    }

    public function setNpmBinary(string $npmBinary): static
    {
        $this->capturedNpmBinary = $npmBinary;

        return $this;
    }

    public function setChromePath(string $executablePath): static
    {
        $this->capturedChromePath = $executablePath;

        return $this;
    }

    public function noSandbox(): static
    {
        $this->capturedSandboxDisabled = true;

        return $this;
    }

    public function pdf(): string
    {
        return "%PDF-1.4\n% fake browsershot pdf\n";
    }
}
