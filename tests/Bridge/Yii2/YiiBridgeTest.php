<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Bridge\Yii2;

use PdfInvoices\Core\Bridge\Yii2\Http\InvoiceResponseFactory;
use PdfInvoices\Core\Bridge\Yii2\PdfInvoicesBootstrap;
use PdfInvoices\Core\Bridge\Yii2\PdfInvoicesComponent;
use PdfInvoices\Core\Pdf\GeneratedDocument;
use PdfInvoices\Core\Tests\Bridge\Support\BridgeHtmlPdfEngine;
use PdfInvoices\Core\Tests\Bridge\Support\InvoiceFactory;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\Application;
use yii\web\Response;

final class YiiBridgeTest extends TestCase
{
    public function testBootstrapRegistersComponentWhenMissing(): void
    {
        $app = new Application();

        (new PdfInvoicesBootstrap())->bootstrap($app);

        self::assertTrue($app->has('pdfInvoices'));
        self::assertSame(PdfInvoicesComponent::class, $app->components['pdfInvoices']);
    }

    public function testComponentGeneratesDocumentWithConfiguredEngine(): void
    {
        Yii::$app = new Application();
        Yii::$app->response = new Response();

        $component = new PdfInvoicesComponent();
        $component->engine = BridgeHtmlPdfEngine::class;
        $component->template = 'minimal';

        $document = $component->generate(InvoiceFactory::make());

        self::assertSame('text/html', $document->mimeType());
        self::assertStringContainsString('Consultoria', $document->contents());
    }

    public function testResponseFactorySendsGeneratedDocumentAsFile(): void
    {
        Yii::$app = new Application();
        Yii::$app->response = new Response();

        $response = (new InvoiceResponseFactory())->download(
            new GeneratedDocument('PDF', 'application/pdf'),
            'invoice.pdf',
        );

        self::assertSame(Yii::$app->response, $response);
        self::assertSame('PDF', $response->content);
        self::assertSame('invoice.pdf', $response->attachmentName);
        self::assertSame(['mimeType' => 'application/pdf', 'inline' => false], $response->options);
    }
}
