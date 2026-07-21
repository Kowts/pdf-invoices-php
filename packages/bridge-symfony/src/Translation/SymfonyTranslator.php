<?php

declare(strict_types=1);

namespace PdfInvoices\Symfony\Translation;

use PdfInvoices\Core\Contract\TranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface as SymfonyTranslatorInterface;

final readonly class SymfonyTranslator implements TranslatorInterface
{
    public function __construct(private SymfonyTranslatorInterface $translator)
    {
    }

    public function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $parameters = [];
        foreach ($replace as $name => $value) {
            $parameters['%' . $name . '%'] = $value;
        }

        return $this->translator->trans('invoice.' . $key, $parameters, 'pdf_invoices', $locale);
    }
}
