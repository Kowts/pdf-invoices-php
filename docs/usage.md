# Utilização

## PHP puro

```php
use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;

$invoice = InvoiceBuilder::create()
    ->seller(PartyBuilder::create()->name('Empresa')->build())
    ->buyer(PartyBuilder::create()->name('Cliente')->build())
    ->number('FT 2026/001')
    ->currency('CVE')
    ->locale('pt_PT')
    ->addItem(
        ItemBuilder::create()
            ->description('Serviços')
            ->unitPrice(Money::fromDecimal('1500.00', 'CVE'))
            ->quantity(Quantity::fromDecimal('2.5'))
            ->tax(Percentage::fromBasisPoints(1500))
            ->build()
    )
    ->build();

$document = InvoiceGenerator::defaultHtmlPreview()->generate($invoice);
$document->save(__DIR__ . '/invoice-preview.html');
```

Para PDF real, instalar `dompdf/dompdf` e injetar `DompdfEngine` no
`InvoiceGenerator`.

## Laravel

```php
use PdfInvoices\Core\InvoiceGenerator;

final class InvoiceController
{
    public function download(InvoiceGenerator $generator)
    {
        $document = $generator->generate($invoice, config('pdf-invoices.template'));

        return response()->streamDownload(
            fn () => print $document->contents(),
            'invoice.pdf',
            ['Content-Type' => $document->mimeType()],
        );
    }
}
```

A facade existe como conveniência, mas não é obrigatória.

## Yii2

Configuração mínima em `config/web.php`:

```php
'components' => [
    'pdfInvoices' => [
        'class' => \PdfInvoices\Yii2\PdfInvoicesComponent::class,
        'template' => 'modern',
        'locale' => 'pt_PT',
    ],
],
```

Uso:

```php
$document = Yii::$app->pdfInvoices->generate($invoice);
```

## Symfony

Configuração mínima:

```yaml
pdf_invoices:
  template: modern
```

Uso por injeção:

```php
use PdfInvoices\Core\InvoiceGenerator;

final readonly class InvoiceController
{
    public function __construct(private InvoiceGenerator $generator) {}

    public function __invoke(): Response
    {
        $document = $this->generator->generate($invoice);

        return new Response($document->contents(), 200, [
            'Content-Type' => $document->mimeType(),
        ]);
    }
}
```

