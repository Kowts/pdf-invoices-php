<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Template;

use PdfInvoices\Core\Contract\TemplateResolverInterface;
use PdfInvoices\Core\Exception\InvoiceException;

final readonly class FilesystemTemplateResolver implements TemplateResolverInterface
{
    /**
     * @param list<string> $paths
     */
    public function __construct(private array $paths)
    {
    }

    public static function default(): self
    {
        return new self([dirname(__DIR__, 2) . '/resources/templates']);
    }

    public function resolve(string $template): string
    {
        if (! preg_match('/^[a-zA-Z0-9_-]+$/', $template)) {
            throw new InvoiceException('Template name contains invalid characters.');
        }

        foreach ($this->paths as $path) {
            $candidate = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $template . '.php';
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        throw new InvoiceException("Template [{$template}] was not found.");
    }
}

