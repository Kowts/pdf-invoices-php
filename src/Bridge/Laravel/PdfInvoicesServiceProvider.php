<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Bridge\Laravel;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use PdfInvoices\Core\Bridge\Laravel\Translation\LaravelTranslator;
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

final class PdfInvoicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $configPath = dirname(__DIR__, 3) . '/config/pdf-invoices.php';
        $this->mergeConfigFrom($configPath, 'pdf-invoices');

        $this->app->singleton(TranslatorInterface::class, LaravelTranslator::class);
        $this->app->singleton(CurrencyFormatterInterface::class, SimpleCurrencyFormatter::class);
        $this->app->singleton(InvoiceValidatorInterface::class, DefaultInvoiceValidator::class);
        $this->app->singleton(InvoiceCalculator::class);

        $this->app->singleton(TemplateRendererInterface::class, static fn (): TemplateRendererInterface => new NativePhpTemplateRenderer(FilesystemTemplateResolver::default()));

        $this->app->singleton(PdfEngineInterface::class, static function (Container $app): PdfEngineInterface {
            $config = $app->make(Repository::class);
            $engine = $config->get('pdf-invoices.engine', DompdfEngine::class);

            if (! is_string($engine)) {
                $engine = DompdfEngine::class;
            }

            $instance = $app->make($engine);
            if (! $instance instanceof PdfEngineInterface) {
                throw new InvalidArgumentException("PDF engine {$engine} must implement " . PdfEngineInterface::class);
            }

            return $instance;
        });

        $this->app->singleton(InvoiceGenerator::class, static fn (Container $app): InvoiceGenerator => new InvoiceGenerator(
            $app->make(PdfEngineInterface::class),
            $app->make(TemplateRendererInterface::class),
            $app->make(InvoiceCalculator::class),
            $app->make(TranslatorInterface::class),
            $app->make(CurrencyFormatterInterface::class),
            $app->make(InvoiceValidatorInterface::class),
        ));
    }

    public function boot(): void
    {
        $target = (getcwd() ?: '.') . '/config/pdf-invoices.php';

        $this->publishes([
            dirname(__DIR__, 3) . '/config/pdf-invoices.php' => $target,
        ], 'pdf-invoices-config');
    }
}
