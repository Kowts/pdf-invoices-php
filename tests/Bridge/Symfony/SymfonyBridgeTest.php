<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Bridge\Symfony;

use PdfInvoices\Core\Bridge\Symfony\DependencyInjection\PdfInvoicesExtension;
use PdfInvoices\Core\Bridge\Symfony\Http\InvoiceResponseFactory;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Pdf\GeneratedDocument;
use PdfInvoices\Core\Tests\Bridge\Support\InvoiceFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\Translation\TranslatorInterface;

final class SymfonyBridgeTest extends TestCase
{
    public function testExtensionRegistersInvoiceGeneratorService(): void
    {
        $container = new ContainerBuilder();
        $container->set('translator', new FakeSymfonyTranslator());

        (new PdfInvoicesExtension())->load([['template' => 'minimal']], $container);
        $container->compile();

        $generator = $container->get(InvoiceGenerator::class);
        self::assertInstanceOf(InvoiceGenerator::class, $generator);

        $document = $generator->generate(InvoiceFactory::make(), 'minimal');

        self::assertSame('minimal', $container->getParameter('pdf_invoices.default_template'));
        self::assertSame('application/pdf', $document->mimeType());
        self::assertStringStartsWith('%PDF-', $document->contents());
    }

    public function testResponseFactoryReturnsDownloadResponse(): void
    {
        $response = (new InvoiceResponseFactory())->download(
            new GeneratedDocument('PDF', 'application/pdf'),
            'invoice.pdf',
        );

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('PDF', $response->getContent());
        self::assertSame('application/pdf', $response->headers->get('Content-Type'));
        self::assertSame('attachment; filename="invoice.pdf"', $response->headers->get('Content-Disposition'));
    }
}

final class FakeSymfonyTranslator implements TranslatorInterface
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $id;
    }

    public function getLocale(): string
    {
        return 'pt_PT';
    }
}
