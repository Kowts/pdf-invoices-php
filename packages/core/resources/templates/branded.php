<?php

declare(strict_types=1);

use PdfInvoices\Core\Support\Escaper;

require_once __DIR__ . '/partials.php';
$locale = $invoice->locale;
$accent = (string) ($theme['accent'] ?? '#1d4ed8');
?>
<!doctype html>
<html lang="<?= Escaper::html($locale ?? 'en') ?>">
<head>
    <meta charset="utf-8">
    <style>
        body { color: #111827; font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; margin: 0; }
        .band { background: <?= Escaper::html($accent) ?>; color: #fff; margin: -8px -8px 26px; padding: 22px 24px; }
        h1 { font-size: 28px; margin: 0; }
        h2 { font-size: 13px; margin: 0 0 8px; text-transform: uppercase; }
        .row { display: table; width: 100%; }
        .col { display: table-cell; width: 50%; vertical-align: top; }
        .meta { text-align: right; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 9px 7px; text-align: left; }
        th { color: <?= Escaper::html($accent) ?>; }
        th.money, td.money { text-align: right; }
        .parties { margin-bottom: 24px; }
        .totals { margin-left: auto; margin-top: 18px; width: 310px; }
        .total td { background: #f3f4f6; font-size: 14px; font-weight: bold; }
        .notes { border-left: 4px solid <?= Escaper::html($accent) ?>; margin-top: 24px; padding-left: 12px; }
    </style>
</head>
<body>
    <div class="band row">
        <div class="col"><h1><?= Escaper::html($t->trans('invoice', [], $locale)) ?></h1></div>
        <div class="col meta">
            <?php if ($invoice->number !== null): ?>
                <?= Escaper::html($invoice->number) ?><br>
            <?php endif; ?>
            <?php if ($invoice->issuedAt !== null): ?>
                <?= Escaper::html($invoice->issuedAt->format('Y-m-d')) ?>
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

