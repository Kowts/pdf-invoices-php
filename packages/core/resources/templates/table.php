<?php

declare(strict_types=1);

use PdfInvoices\Core\Support\Escaper;

?>
<table>
    <thead>
        <tr>
            <th><?= Escaper::html($t->trans('description', [], $locale)) ?></th>
            <th><?= Escaper::html($t->trans('quantity', [], $locale)) ?></th>
            <th class="money"><?= Escaper::html($t->trans('unit_price', [], $locale)) ?></th>
            <th class="money"><?= Escaper::html($t->trans('subtotal', [], $locale)) ?></th>
            <th class="money"><?= Escaper::html($t->trans('total', [], $locale)) ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($invoice->items as $index => $item): ?>
            <tr>
                <td><?= Escaper::html($item->description) ?></td>
                <td><?= Escaper::html($item->quantity->toDecimalString()) ?></td>
                <td class="money"><?= Escaper::html($money->format($item->unitPrice, $locale)) ?></td>
                <td class="money"><?= Escaper::html($money->format($totals->lines[$index]->subtotal, $locale)) ?></td>
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
