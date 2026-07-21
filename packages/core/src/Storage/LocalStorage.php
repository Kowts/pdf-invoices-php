<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Storage;

use PdfInvoices\Core\Contract\StorageInterface;
use PdfInvoices\Core\Exception\InvoiceException;

final readonly class LocalStorage implements StorageInterface
{
    public function __construct(private string $basePath)
    {
    }

    public function put(string $path, string $contents): string
    {
        $target = $this->resolvePath($path);
        $directory = dirname($target);

        if (! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        file_put_contents($target, $contents);

        return $target;
    }

    public function get(string $path): string
    {
        $target = $this->resolvePath($path);
        $contents = is_file($target) ? file_get_contents($target) : false;

        if ($contents === false) {
            throw new InvoiceException("File [{$path}] was not found.");
        }

        return $contents;
    }

    public function exists(string $path): bool
    {
        return is_file($this->resolvePath($path));
    }

    public function delete(string $path): bool
    {
        $target = $this->resolvePath($path);

        return is_file($target) && unlink($target);
    }

    private function resolvePath(string $path): string
    {
        if (str_contains($path, "\0") || str_contains(str_replace('\\', '/', $path), '../')) {
            throw new InvoiceException('Storage path is not allowed.');
        }

        $base = rtrim($this->basePath, DIRECTORY_SEPARATOR);
        $target = $base . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
        $baseReal = realpath($base) ?: $base;
        $targetDirectory = dirname($target);

        if (! is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0775, true);
        }

        $directoryReal = realpath($targetDirectory) ?: $targetDirectory;
        if (! str_starts_with($directoryReal, $baseReal)) {
            throw new InvoiceException('Storage path escapes the base directory.');
        }

        return $target;
    }
}
