<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Bridge\Symfony\DependencyInjection;

use PdfInvoices\Core\Bridge\Symfony\Translation\SymfonyTranslator;
use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\Contract\CurrencyFormatterInterface;
use PdfInvoices\Core\Contract\InvoiceValidatorInterface;
use PdfInvoices\Core\Contract\PdfEngineInterface;
use PdfInvoices\Core\Contract\TemplateRendererInterface;
use PdfInvoices\Core\Contract\TranslatorInterface;
use PdfInvoices\Core\Formatting\SimpleCurrencyFormatter;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Pdf\DompdfEngine;
use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PdfInvoices\Core\Template\NativePhpTemplateRenderer;
use PdfInvoices\Core\Validation\DefaultInvoiceValidator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class PdfInvoicesExtension extends Extension
{
    /**
     * @param array<int, array<string, mixed>> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $template = $configs[0]['template'] ?? 'modern';
        if (! is_string($template)) {
            $template = 'modern';
        }

        $container->setParameter('pdf_invoices.default_template', $template);

        $container->setDefinition(PdfEngineInterface::class, new Definition(DompdfEngine::class));
        $container->setDefinition(InvoiceCalculator::class, new Definition(InvoiceCalculator::class));
        $container->setDefinition(CurrencyFormatterInterface::class, new Definition(SimpleCurrencyFormatter::class));
        $container->setDefinition(InvoiceValidatorInterface::class, new Definition(DefaultInvoiceValidator::class));
        $container->setDefinition(TranslatorInterface::class, new Definition(SymfonyTranslator::class, [
            new Reference('translator'),
        ]));
        $container->setDefinition('pdf_invoices.template_resolver', new Definition(FilesystemTemplateResolver::class, [[
            dirname(__DIR__, 4) . '/resources/templates',
        ]]));
        $container->setDefinition(TemplateRendererInterface::class, new Definition(NativePhpTemplateRenderer::class, [
            new Reference('pdf_invoices.template_resolver'),
        ]));
        $container->setDefinition(InvoiceGenerator::class, new Definition(InvoiceGenerator::class, [
            new Reference(PdfEngineInterface::class),
            new Reference(TemplateRendererInterface::class),
            new Reference(InvoiceCalculator::class),
            new Reference(TranslatorInterface::class),
            new Reference(CurrencyFormatterInterface::class),
            new Reference(InvoiceValidatorInterface::class),
        ]))->setPublic(true);
    }
}
