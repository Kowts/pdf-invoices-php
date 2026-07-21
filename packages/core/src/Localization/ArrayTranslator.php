<?php

declare(strict_types=1);

namespace PdfInvoices\Core\Localization;

use PdfInvoices\Core\Contract\TranslatorInterface;

final readonly class ArrayTranslator implements TranslatorInterface
{
    /**
     * @param array<string, array<string, string>> $catalogues
     */
    public function __construct(
        private array $catalogues,
        private string $defaultLocale = 'en',
    ) {
    }

    public static function default(): self
    {
        /** @var array<string, string> $english */
        $english = require dirname(__DIR__, 2) . '/resources/translations/en.php';
        /** @var array<string, string> $portuguese */
        $portuguese = require dirname(__DIR__, 2) . '/resources/translations/pt_PT.php';

        return new self([
            'en' => $english,
            'pt_PT' => $portuguese,
            'pt' => $portuguese,
        ]);
    }

    public function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale ??= $this->defaultLocale;
        $line = $this->catalogues[$locale][$key] ?? $this->catalogues[$this->defaultLocale][$key] ?? $key;

        foreach ($replace as $name => $value) {
            $line = str_replace(':' . $name, (string) $value, $line);
        }

        return $line;
    }
}
