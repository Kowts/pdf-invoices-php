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
        $normalizedRelativePath = str_replace('\\', '/', $path);

        if (
            str_contains($path, "\0")
            || str_contains($normalizedRelativePath, '../')
            || str_starts_with($normalizedRelativePath, '/')
            || preg_match('/^[a-zA-Z]:\//', $normalizedRelativePath) === 1
        ) {
            throw new InvoiceException('Storage path is not allowed.');
        }

        $base = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $this->basePath), DIRECTORY_SEPARATOR);

        if (! is_dir($base)) {
            mkdir($base, 0775, true);
        }

        $baseReal = realpath($base) ?: $base;
        $target = $baseReal . DIRECTORY_SEPARATOR . ltrim(
            str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path),
            DIRECTORY_SEPARATOR,
        );
        $targetDirectory = dirname($target);

        if (! is_dir($targetDirectory)) {
            mkdir($targetDirectory, 0775, true);
        }

        $directoryReal = realpath($targetDirectory) ?: $targetDirectory;
        $normalizedBase = $this->normalizePath($baseReal);
        $normalizedDirectory = $this->normalizePath($directoryReal);

        if (
            $normalizedDirectory !== $normalizedBase
            && ! str_starts_with($normalizedDirectory, $normalizedBase . '/')
        ) {
            throw new InvoiceException('Storage path escapes the base directory.');
        }

        return $target;
    }

    private function normalizePath(string $path): string
    {
        $normalized = rtrim(str_replace('\\', '/', $path), '/');

        return DIRECTORY_SEPARATOR === '\\' ? strtolower($normalized) : $normalized;
    }
}
