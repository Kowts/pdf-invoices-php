<?php

declare(strict_types=1);

namespace PdfInvoices\Laravel;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
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
use PdfInvoices\Laravel\Translation\LaravelTranslator;

final class PdfInvoicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pdf-invoices.php', 'pdf-invoices');

        $this->app->singleton(TranslatorInterface::class, LaravelTranslator::class);
        $this->app->singleton(CurrencyFormatterInterface::class, SimpleCurrencyFormatter::class);
        $this->app->singleton(InvoiceValidatorInterface::class, DefaultInvoiceValidator::class);
        $this->app->singleton(InvoiceCalculator::class);

        $this->app->singleton(TemplateRendererInterface::class, static function (): TemplateRendererInterface {
            return new NativePhpTemplateRenderer(FilesystemTemplateResolver::default());
        });

        $this->app->singleton(PdfEngineInterface::class, static function (Container $app): PdfEngineInterface {
            $engine = (string) $app['config']->get('pdf-invoices.engine', DompdfEngine::class);

            return $app->make($engine);
        });

        $this->app->singleton(InvoiceGenerator::class, static function (Container $app): InvoiceGenerator {
            return new InvoiceGenerator(
                $app->make(PdfEngineInterface::class),
                $app->make(TemplateRendererInterface::class),
                $app->make(InvoiceCalculator::class),
                $app->make(TranslatorInterface::class),
                $app->make(CurrencyFormatterInterface::class),
                $app->make(InvoiceValidatorInterface::class),
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/pdf-invoices.php' => config_path('pdf-invoices.php'),
        ], 'pdf-invoices-config');
    }
}
