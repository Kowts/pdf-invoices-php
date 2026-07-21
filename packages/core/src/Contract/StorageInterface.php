<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Contract;

interface StorageInterface
{
    public function put(string $path, string $contents): string;

    public function get(string $path): string;

    public function exists(string $path): bool;

    public function delete(string $path): bool;
}

