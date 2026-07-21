<?php

declare(strict_types=1);

namespace app\controllers;

use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;
use Yii;
use yii\web\Controller;
use yii\web\Response;

final class InvoiceController extends Controller
{
    public function actionDownload(): Response
    {
        $invoice = InvoiceBuilder::create()
            ->seller(PartyBuilder::create()->name('Empresa Yii2')->build())
            ->buyer(PartyBuilder::create()->name('Cliente Yii2')->build())
            ->number('FT-YII2-001')
            ->currency('EUR')
            ->locale('pt_PT')
            ->addItem(
                ItemBuilder::create()
                    ->description('Desenvolvimento Yii2')
                    ->unitPrice(Money::fromDecimal('650.00', 'EUR'))
                    ->quantity(Quantity::fromInt(1))
                    ->tax(Percentage::fromBasisPoints(2300))
                    ->build(),
            )
            ->build();

        $document = Yii::$app->pdfInvoices->generate($invoice);

        return Yii::$app->response->sendContentAsFile(
            $document->contents(),
            'invoice.pdf',
            ['mimeType' => $document->mimeType(), 'inline' => false],
        );
    }
}

