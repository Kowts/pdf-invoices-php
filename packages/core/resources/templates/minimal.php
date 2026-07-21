<?php

declare(strict_types=1);

use PdfInvoices\Core\Support\Escaper;

require_once __DIR__ . '/partials.php';
$locale = $invoice->locale;
?>
<!doctype html>
<html lang="<?= Escaper::html($locale ?? 'en') ?>">
<head>
    <meta charset="utf-8">
    <style>
        body { color: #222; font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; margin: 0; }
        h1 { font-size: 24px; margin: 0 0 12px; }
        .meta, .parties { display: table; width: 100%; margin-bottom: 22px; }
        .cell { display: table-cell; width: 50%; vertical-align: top; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid #ddd; padding: 8px 6px; text-align: left; }
        th.money, td.money { text-align: right; }
        .totals { margin-left: auto; margin-top: 18px; width: 280px; }
        .total { font-weight: bold; }
        .notes { margin-top: 24px; }
    </style>
</head>
<body>
    <h1><?= Escaper::html($t->trans('invoice', [], $locale)) ?></h1>
    <div class="meta">
        <div class="cell">
            <?php if ($invoice->number !== null): ?>
                <?= Escaper::html($t->trans('invoice_number', [], $locale)) ?>:
                <?= Escaper::html($invoice->number) ?><br>
            <?php endif; ?>
            <?php if ($invoice->issuedAt !== null): ?>
                <?= Escaper::html($t->trans('issue_date', [], $locale)) ?>:
                <?= Escaper::html($invoice->issuedAt->format('Y-m-d')) ?><br>
            <?php endif; ?>
            <?php if ($invoice->dueAt !== null): ?>
                <?= Escaper::html($t->trans('due_date', [], $locale)) ?>:
                <?= Escaper::html($invoice->dueAt->format('Y-m-d')) ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="parties">
        <div class="cell">
            <h2><?= Escaper::html($t->trans('seller', [], $locale)) ?></h2>
            <?= pdf_invoices_party_block($invoice->seller) ?>
        </div>
        <div class="cell">
            <h2><?= Escaper::html($t->trans('buyer', [], $locale)) ?></h2>
            <?= pdf_invoices_party_block($invoice->buyer) ?>
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
        <tr><td><?= Escaper::html($t->trans('subtotal', [], $locale)) ?></td><td class="money"><?= Escaper::html($money->format($totals->subtotal, $locale)) ?></td></tr>
        <tr><td><?= Escaper::html($t->trans('discount', [], $locale)) ?></td><td class="money"><?= Escaper::html($money->format($totals->lineDiscounts->add($totals->globalDiscount), $locale)) ?></td></tr>
        <tr><td><?= Escaper::html($t->trans('tax', [], $locale)) ?></td><td class="money"><?= Escaper::html($money->format($totals->tax, $locale)) ?></td></tr>
        <tr><td><?= Escaper::html($t->trans('withholding', [], $locale)) ?></td><td class="money"><?= Escaper::html($money->format($totals->withholding, $locale)) ?></td></tr>
        <tr class="total"><td><?= Escaper::html($t->trans('total', [], $locale)) ?></td><td class="money"><?= Escaper::html($money->format($totals->total, $locale)) ?></td></tr>
    </table>
    <?php if ($invoice->notes !== null): ?>
        <div class="notes">
            <strong><?= Escaper::html($t->trans('notes', [], $locale)) ?></strong><br>
            <?= nl2br(Escaper::html($invoice->notes)) ?>
        </div>
    <?php endif; ?>
</body>
</html>
