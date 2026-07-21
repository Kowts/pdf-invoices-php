<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Bridge\Laravel\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use PdfInvoices\Core\Contract\StorageInterface;

final readonly class LaravelStorage implements StorageInterface
{
    public function __construct(private Filesystem $disk)
    {
    }

    public function put(string $path, string $contents): string
    {
        $this->disk->put($path, $contents);

        return $path;
    }

    public function get(string $path): string
    {
        return (string) $this->disk->get($path);
    }

    public function exists(string $path): bool
    {
        return $this->disk->exists($path);
    }

    public function delete(string $path): bool
    {
        return $this->disk->delete($path);
    }
}
