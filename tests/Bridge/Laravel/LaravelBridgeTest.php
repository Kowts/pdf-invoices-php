<?php

declare(strict_types=1);

namespace {
    if (! function_exists('env')) {
        function env(string $key, mixed $default = null): mixed
        {
            return $default;
        }
    }

    if (! function_exists('storage_path')) {
        function storage_path(string $path = ''): string
        {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

            return rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . ($path === '' ? '' : DIRECTORY_SEPARATOR . $path);
        }
    }
}

namespace PdfInvoices\Core\Tests\Bridge\Laravel {
    use Illuminate\Config\Repository as ConfigRepository;
    use Illuminate\Contracts\Config\Repository;
    use Illuminate\Contracts\Translation\Translator;
    use Illuminate\Support\ServiceProvider;
    use PdfInvoices\Core\Bridge\Laravel\PdfInvoicesServiceProvider;
    use PdfInvoices\Core\Contract\PdfEngineInterface;
    use PdfInvoices\Core\InvoiceGenerator;
    use PdfInvoices\Core\Tests\Bridge\Support\BridgeHtmlPdfEngine;
    use PdfInvoices\Core\Tests\Bridge\Support\InvoiceFactory;
    use PHPUnit\Framework\TestCase;

    final class LaravelBridgeTest extends TestCase
    {
        protected function setUp(): void
        {
            ServiceProvider::$publishes = [];
            ServiceProvider::$publishGroups = [];
        }

        public function testServiceProviderRegistersInvoiceGeneratorWithConfiguredEngine(): void
        {
            $app = new LaravelApplication();
            $config = new ConfigRepository([
                'pdf-invoices' => [
                    'engine' => BridgeHtmlPdfEngine::class,
                ],
            ]);

            $app->instance('config', $config);
            $app->instance(Repository::class, $config);
            $app->instance(Translator::class, new FakeLaravelTranslator());

            $provider = new PdfInvoicesServiceProvider($app);
            $provider->register();

            $engine = $app->make(PdfEngineInterface::class);
            $generator = $app->make(InvoiceGenerator::class);
            $document = $generator->generate(InvoiceFactory::make(), 'minimal');

            self::assertInstanceOf(BridgeHtmlPdfEngine::class, $engine);
            self::assertSame('text/html', $document->mimeType());
            self::assertStringContainsString('Consultoria', $document->contents());
            self::assertSame(BridgeHtmlPdfEngine::class, $config->get('pdf-invoices.engine'));
        }

        public function testServiceProviderPublishesConfigFile(): void
        {
            $app = new LaravelApplication();
            $app->instance('config', new ConfigRepository());

            $provider = new PdfInvoicesServiceProvider($app);
            $provider->boot();

            $paths = ServiceProvider::pathsToPublish(PdfInvoicesServiceProvider::class, 'pdf-invoices-config');

            self::assertCount(1, $paths);
            self::assertFileExists((string) array_key_first($paths));
            self::assertStringEndsWith('config/pdf-invoices.php', str_replace('\\', '/', (string) current($paths)));
        }
    }

    final class FakeLaravelTranslator implements Translator
    {
        /**
         * @param array<string, string> $replace
         */
        public function get($key, array $replace = [], $locale = null)
        {
            return (string) $key;
        }

        /**
         * @param array<int|string, mixed>|\Countable|float|int $number
         * @param array<string, string> $replace
         */
        public function choice($key, $number, array $replace = [], $locale = null)
        {
            return (string) $key;
        }

        public function getLocale()
        {
            return 'pt_PT';
        }

        public function setLocale($locale)
        {
        }
    }
}
