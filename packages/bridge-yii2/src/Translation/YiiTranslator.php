<?php

declare(strict_types=1);

namespace PdfInvoices\Yii2\Translation;

use PdfInvoices\Core\Contract\TranslatorInterface;
use Yii;

final class YiiTranslator implements TranslatorInterface
{
    public function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $params = [];
        foreach ($replace as $name => $value) {
            $params['{' . $name . '}'] = $value;
        }

        return Yii::t('pdf-invoices', $key, $params, $locale);
    }
}
