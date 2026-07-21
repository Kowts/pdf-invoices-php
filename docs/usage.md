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

## Templates customizados

Pode usar os templates oficiais por nome:

```php
$document = InvoiceGenerator::defaultHtmlPreview()
    ->generate($invoice, 'modern');
```

Para adicionar templates da aplicacao sem perder os templates oficiais, crie um
diretorio com ficheiros `*.php` e configure o resolver com
`FilesystemTemplateResolver::withDefaultTemplates()`:

```php
use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\Formatting\SimpleCurrencyFormatter;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Localization\ArrayTranslator;
use PdfInvoices\Core\Pdf\HtmlPreviewEngine;
use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PdfInvoices\Core\Template\NativePhpTemplateRenderer;
use PdfInvoices\Core\Validation\DefaultInvoiceValidator;

$generator = new InvoiceGenerator(
    new HtmlPreviewEngine(),
    new NativePhpTemplateRenderer(
        FilesystemTemplateResolver::withDefaultTemplates(__DIR__ . '/templates'),
    ),
    new InvoiceCalculator(),
    ArrayTranslator::default(),
    new SimpleCurrencyFormatter(),
    new DefaultInvoiceValidator(),
);

$document = $generator->generate(
    $invoice,
    'compact-brand',
    theme: [
        'accent' => '#7c3aed',
        'brand' => 'Kowts Studio',
        'footer' => 'Obrigado pela preferencia.',
    ],
);
```

O ficheiro `templates/compact-brand.php` recebe as variaveis `$invoice`,
`$totals`, `$t`, `$money` e `$theme`. Templates personalizados sao executados
como PHP, por isso devem ser tratados como codigo confiavel.

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
        'class' => \PdfInvoices\Core\Bridge\Yii2\PdfInvoicesComponent::class,
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
