<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Template;

use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PHPUnit\Framework\TestCase;

final class FilesystemTemplateResolverTest extends TestCase
{
    public function testItResolvesCustomTemplatesBeforeDefaultTemplates(): void
    {
        $basePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pdf-invoices-template-' . uniqid();
        mkdir($basePath);

        $customTemplate = $basePath . DIRECTORY_SEPARATOR . 'modern.php';
        file_put_contents($customTemplate, '<?php echo "custom";');

        $resolver = FilesystemTemplateResolver::withDefaultTemplates($basePath);

        self::assertSame($customTemplate, $resolver->resolve('modern'));
        self::assertFileExists($resolver->resolve('minimal'));

        unlink($customTemplate);
        rmdir($basePath);
    }
}
