<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Pdf;

use PdfInvoices\Core\Contract\StorageInterface;

final readonly class GeneratedDocument
{
    public function __construct(
        private string $contents,
        private string $mimeType = 'application/pdf',
    ) {
    }

    public function contents(): string
    {
        return $this->contents;
    }

    public function mimeType(): string
    {
        return $this->mimeType;
    }

    public function save(string $path): string
    {
        $directory = dirname($path);
        if ($directory !== '.' && ! is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        file_put_contents($path, $this->contents);

        return $path;
    }

    public function store(StorageInterface $storage, string $path): string
    {
        return $storage->put($path, $this->contents);
    }
}

