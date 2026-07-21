<?php

declare(strict_types=1);

use PdfInvoices\Core\Support\Escaper;

$locale = $invoice->locale;
$accent = (string) ($theme['accent'] ?? '#2563eb');
$brand = (string) ($theme['brand'] ?? $invoice->seller->name);
$footer = (string) ($theme['footer'] ?? '');
?>
<!doctype html>
<html lang="<?= Escaper::html($locale ?? 'en') ?>">
<head>
    <meta charset="utf-8">
    <style>
        body { color: #1f2937; font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; margin: 0; }
        .header { border-bottom: 3px solid <?= Escaper::html($accent) ?>; margin-bottom: 20px; padding-bottom: 12px; }
        .brand { color: <?= Escaper::html($accent) ?>; font-size: 22px; font-weight: bold; }
        .muted { color: #6b7280; }
        .grid { display: table; margin-bottom: 18px; width: 100%; }
        .cell { display: table-cell; vertical-align: top; width: 50%; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f3f4f6; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 8px 6px; text-align: left; }
        .money { text-align: right; }
        .totals { margin-left: auto; margin-top: 16px; width: 280px; }
        .total td { border-top: 2px solid <?= Escaper::html($accent) ?>; font-weight: bold; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; margin-top: 24px; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand"><?= Escaper::html($brand) ?></div>
        <div class="muted">
            <?= Escaper::html($t->trans('invoice', [], $locale)) ?>
            <?php if ($invoice->number !== null): ?>
                #<?= Escaper::html($invoice->number) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid">
        <div class="cell">
            <strong><?= Escaper::html($t->trans('seller', [], $locale)) ?></strong><br>
            <?= Escaper::html($invoice->seller->name) ?><br>
            <?php if ($invoice->seller->taxNumber !== null): ?>
                <?= Escaper::html($invoice->seller->taxNumber) ?>
            <?php endif; ?>
        </div>
        <div class="cell">
            <strong><?= Escaper::html($t->trans('buyer', [], $locale)) ?></strong><br>
            <?= Escaper::html($invoice->buyer->name) ?><br>
            <?php if ($invoice->buyer->taxNumber !== null): ?>
                <?= Escaper::html($invoice->buyer->taxNumber) ?>
            <?php endif; ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th><?= Escaper::html($t->trans('description', [], $locale)) ?></th>
                <th><?= Escaper::html($t->trans('quantity', [], $locale)) ?></th>
                <th class="money"><?= Escaper::html($t->trans('unit_price', [], $locale)) ?></th>
                <th class="money"><?= Escaper::html($t->trans('total', [], $locale)) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoice->items as $index => $item): ?>
                <tr>
                    <td><?= Escaper::html($item->description) ?></td>
                    <td><?= Escaper::html($item->quantity->toDecimalString()) ?></td>
                    <td class="money"><?= Escaper::html($money->format($item->unitPrice, $locale)) ?></td>
                    <td class="money"><?= Escaper::html($money->format($totals->lines[$index]->total, $locale)) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td><?= Escaper::html($t->trans('subtotal', [], $locale)) ?></td>
            <td class="money"><?= Escaper::html($money->format($totals->subtotal, $locale)) ?></td>
        </tr>
        <tr class="total">
            <td><?= Escaper::html($t->trans('total', [], $locale)) ?></td>
            <td class="money"><?= Escaper::html($money->format($totals->total, $locale)) ?></td>
        </tr>
    </table>

    <?php if ($footer !== ''): ?>
        <div class="footer"><?= Escaper::html($footer) ?></div>
    <?php endif; ?>
</body>
</html>
