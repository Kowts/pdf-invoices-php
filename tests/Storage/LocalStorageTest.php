<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Tests\Storage;

use PdfInvoices\Core\Storage\LocalStorage;
use PHPUnit\Framework\TestCase;

final class LocalStorageTest extends TestCase
{
    public function testItStoresFilesWhenBasePathUsesForwardSlashes(): void
    {
        $basePath = str_replace('\\', '/', sys_get_temp_dir() . '/pdf-invoices-storage-' . uniqid());
        $storage = new LocalStorage($basePath);

        $path = $storage->put('invoice.html', '<html></html>');

        self::assertFileExists($path);
        self::assertSame('<html></html>', $storage->get('invoice.html'));

        $storage->delete('invoice.html');
        @rmdir($basePath);
    }
}
