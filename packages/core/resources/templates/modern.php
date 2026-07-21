<?php

declare(strict_types=1);

use PdfInvoices\Core\Support\Escaper;

require_once __DIR__ . '/partials.php';
$locale = $invoice->locale;
$accent = (string) ($theme['accent'] ?? '#0f766e');
?>
<!doctype html>
<html lang="<?= Escaper::html($locale ?? 'en') ?>">
<head>
    <meta charset="utf-8">
    <style>
        body { color: #172026; font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; margin: 0; }
        .header { border-bottom: 4px solid <?= Escaper::html($accent) ?>; margin-bottom: 24px; padding-bottom: 16px; }
        h1 { color: <?= Escaper::html($accent) ?>; font-size: 30px; margin: 0; }
        h2 { font-size: 13px; letter-spacing: 0; margin: 0 0 8px; text-transform: uppercase; }
        .row { display: table; width: 100%; }
        .col { display: table-cell; width: 50%; vertical-align: top; }
        .meta { text-align: right; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #eef6f4; color: #172026; }
        th, td { border-bottom: 1px solid #dce5e2; padding: 9px 7px; text-align: left; }
        th.money, td.money { text-align: right; }
        .parties { margin-bottom: 24px; }
        .totals { margin-left: auto; margin-top: 18px; width: 310px; }
        .total td { border-top: 2px solid <?= Escaper::html($accent) ?>; font-size: 14px; font-weight: bold; }
        .notes { background: #f7faf9; margin-top: 24px; padding: 12px; }
    </style>
</head>
<body>
    <div class="header row">
        <div class="col"><h1><?= Escaper::html($t->trans('invoice', [], $locale)) ?></h1></div>
        <div class="col meta">
            <?php if ($invoice->number !== null): ?>
                <strong><?= Escaper::html($invoice->number) ?></strong><br>
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
    <div class="row parties">
        <div class="col">
            <h2><?= Escaper::html($t->trans('seller', [], $locale)) ?></h2>
            <?= pdf_invoices_party_block($invoice->seller) ?>
        </div>
        <div class="col">
            <h2><?= Escaper::html($t->trans('buyer', [], $locale)) ?></h2>
            <?= pdf_invoices_party_block($invoice->buyer) ?>
        </div>
    </div>
    <?php include __DIR__ . '/table.php'; ?>
</body>
</html>
