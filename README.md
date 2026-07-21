# PDF Invoices para PHP

[![Testes](https://img.shields.io/github/actions/workflow/status/Kowts/pdf-invoices-php/ci.yml?branch=main&label=Testes)](https://github.com/Kowts/pdf-invoices-php/actions/workflows/ci.yml)
[![Cobertura](https://img.shields.io/badge/cobertura-%E2%89%A560%25-brightgreen.svg)](https://github.com/Kowts/pdf-invoices-php/actions/workflows/ci.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-4e9a06.svg)](phpstan.neon.dist)
[![PHP](https://img.shields.io/badge/PHP-%5E8.2-777BB4.svg)](https://www.php.net/)
[![Licença](https://img.shields.io/badge/licen%C3%A7a-MIT-blue.svg)](LICENSE)
[![Packagist](https://img.shields.io/packagist/v/kowts/pdf-invoices.svg)](https://packagist.org/packages/kowts/pdf-invoices)
[![Aikido package health](https://img.shields.io/badge/Aikido-package%20health-6f5bf4.svg)](https://intel.aikido.dev/packages/packagist/kowts/pdf-invoices)
![Status](https://img.shields.io/badge/status-beta-orange.svg)

Biblioteca PHP independente de frameworks para construir faturas, calcular
totais monetários com segurança, renderizar templates HTML e gerar documentos
PDF através de motores substituíveis.

O projeto usa um core em PHP puro e bridges oficiais para Laravel, Symfony e
Yii2, mantendo as regras de domínio fora dos frameworks.

> [!IMPORTANT]
> Este projeto gera documentos PDF de faturação, mas não substitui validação
> fiscal, certificação de software, comunicação com autoridades tributárias ou
> requisitos legais específicos de cada país.

## Funcionalidades

- core PHP puro, sem Laravel, Symfony, Yii2, Carbon, Blade, Twig ou facades;
- package Composer unico com bridges opcionais;
- domínio tipado para fatura, linhas, entidades, moradas, percentagens e moeda;
- builders fluentes para `Invoice`, `InvoiceItem` e `Party`;
- cálculos monetários sem `float` como representação principal;
- valores monetários em unidades mínimas, como cêntimos;
- quantidades fracionadas representadas em milésimos;
- descontos por linha e desconto global;
- impostos por linha, incluídos ou excluídos;
- múltiplas taxas por linha;
- retenções;
- validação básica de faturas antes da geração;
- templates PHP nativos `minimal`, `modern` e `branded`;
- tradução base em inglês e português de Portugal;
- formatação monetária simples e substituível;
- armazenamento local seguro contra path traversal básico;
- contrato `PdfEngineInterface` para motores PDF;
- engines opcionais para Dompdf, mPDF, TCPDF e Browsershot;
- preview HTML para testes e desenvolvimento;
- bridges oficiais para Laravel, Yii2 e Symfony;
- responses de download nos bridges;
- testes PHPUnit preparados para o core;
- documentação técnica em português.

## Arquitetura

```text
Aplicações PHP / Laravel / Yii2 / Symfony
    ↓
Bridges e adaptadores
    ↓
kowts/pdf-invoices
    ↓
PHP 8.2+ e contratos independentes
```

O core não conhece os bridges. Os bridges integram containers, tradutores,
filesystems, responses e configuração de cada framework.

Consulte [Arquitetura](docs/architecture.md) para a organização completa.

## Package

O package publicavel e `kowts/pdf-invoices`. O core vive em `src/` e as
integracoes opcionais vivem em `src/Bridge/Laravel`, `src/Bridge/Yii2` e
`src/Bridge/Symfony`.

## Requisitos

- PHP 8.2 ou superior;
- Composer 2;
- extensão `json`;
- um driver PDF opcional: `dompdf/dompdf`, `mpdf/mpdf`,
  `tecnickcom/tcpdf` ou `spatie/browsershot`.

Para desenvolvimento do package:

- PHPUnit 11;
- PHPStan;
- PHP-CS-Fixer;
- Rector.

## Instalação

Quando o package for publicado:

```bash
composer require kowts/pdf-invoices
```

Para usar Dompdf:

```bash
composer require dompdf/dompdf
```

Drivers alternativos:

```bash
composer require mpdf/mpdf
composer require tecnickcom/tcpdf
composer require spatie/browsershot
```

Para testar antes da publicação no Packagist, clone este repositorio e aponte a
aplicação consumidora para ele com um repositório `path`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../pdf-invoices-php/.",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "kowts/pdf-invoices": "^0.1"
    }
}
```

## Utilização rápida

```php
<?php

use PdfInvoices\Core\Builder\InvoiceBuilder;
use PdfInvoices\Core\Builder\ItemBuilder;
use PdfInvoices\Core\Builder\PartyBuilder;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Storage\LocalStorage;
use PdfInvoices\Core\ValueObject\Money;
use PdfInvoices\Core\ValueObject\Percentage;
use PdfInvoices\Core\ValueObject\Quantity;

require __DIR__ . '/vendor/autoload.php';

$seller = PartyBuilder::create()
    ->name('Empresa Exemplo, Lda.')
    ->taxNumber('NIF 123456789')
    ->email('faturacao@example.test')
    ->build();

$buyer = PartyBuilder::create()
    ->name('Cliente Exemplo')
    ->taxNumber('NIF 987654321')
    ->email('cliente@example.test')
    ->build();

$invoice = InvoiceBuilder::create()
    ->seller($seller)
    ->buyer($buyer)
    ->number('FT 2026/001')
    ->currency('CVE')
    ->locale('pt_PT')
    ->addItem(
        ItemBuilder::create()
            ->description('Serviços profissionais')
            ->unitPrice(Money::fromDecimal('1500.00', 'CVE'))
            ->quantity(Quantity::fromDecimal('2.5'))
            ->tax(Percentage::fromBasisPoints(1500))
            ->build()
    )
    ->notes('Pagamento a 30 dias.')
    ->build();

$document = InvoiceGenerator::defaultHtmlPreview()
    ->generate($invoice, 'modern');

$document->store(
    new LocalStorage(__DIR__ . '/build'),
    'invoice-preview.html'
);
```

O exemplo completo está em [examples/plain-php/generate.php](examples/plain-php/generate.php).

## Geração de PDF

O core gera documentos através de `PdfEngineInterface`.

Para produção, instale um driver e injete a engine pretendida no
`InvoiceGenerator`. Exemplo com Dompdf:

```php
use PdfInvoices\Core\Calculation\InvoiceCalculator;
use PdfInvoices\Core\Formatting\SimpleCurrencyFormatter;
use PdfInvoices\Core\InvoiceGenerator;
use PdfInvoices\Core\Localization\ArrayTranslator;
use PdfInvoices\Core\Pdf\DompdfEngine;
use PdfInvoices\Core\Template\FilesystemTemplateResolver;
use PdfInvoices\Core\Template\NativePhpTemplateRenderer;
use PdfInvoices\Core\Validation\DefaultInvoiceValidator;

$generator = new InvoiceGenerator(
    new DompdfEngine(),
    new NativePhpTemplateRenderer(FilesystemTemplateResolver::default()),
    new InvoiceCalculator(),
    ArrayTranslator::default(),
    new SimpleCurrencyFormatter(),
    new DefaultInvoiceValidator()
);

$pdf = $generator->generate($invoice, 'branded');
$pdf->save(__DIR__ . '/invoice.pdf');
```

Drivers disponiveis:

| Driver | Package | Engine |
| --- | --- | --- |
| Dompdf | `dompdf/dompdf` | `DompdfEngine` |
| mPDF | `mpdf/mpdf` | `MpdfEngine` |
| TCPDF | `tecnickcom/tcpdf` | `TcpdfEngine` |
| Browsershot | `spatie/browsershot` + Puppeteer/Chromium | `BrowsershotEngine` |

Recursos remotos e JavaScript ficam restritos por defeito nos drivers onde o
package consegue aplicar essa politica. Ative-os apenas com uma politica de
seguranca adequada.

## Integração com Laravel

Instale o bridge na aplicação Laravel:

```bash
composer require kowts/pdf-invoices
```

Publique a configuração:

```bash
php artisan vendor:publish --tag=pdf-invoices-config
```

Use a API principal por injeção de dependências:

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
            ['Content-Type' => $document->mimeType()]
        );
    }
}
```

A facade existe apenas como conveniência. O uso por container continua a ser a
API recomendada.

## Integração com Yii2

Instale o bridge na aplicação Yii2:

```bash
composer require kowts/pdf-invoices
```

Configuração mínima em `config/web.php`:

```php
use PdfInvoices\Core\Bridge\Yii2\PdfInvoicesComponent;

return [
    'components' => [
        'pdfInvoices' => [
            'class' => PdfInvoicesComponent::class,
            'template' => 'modern',
            'locale' => 'pt_PT',
        ],
    ],
];
```

Depois use o componente:

```php
$document = Yii::$app->pdfInvoices->generate($invoice);
```

O bridge pode ser usado em aplicações web e console.

## Integração com Symfony

Instale o bridge na aplicação Symfony:

```bash
composer require kowts/pdf-invoices
```

Configuração mínima:

```yaml
pdf_invoices:
  template: modern
```

Use por injeção de dependências:

```php
use PdfInvoices\Core\InvoiceGenerator;
use Symfony\Component\HttpFoundation\Response;

final readonly class InvoiceController
{
    public function __construct(private InvoiceGenerator $generator)
    {
    }

    public function __invoke(): Response
    {
        $document = $this->generator->generate($invoice);

        return new Response($document->contents(), 200, [
            'Content-Type' => $document->mimeType(),
        ]);
    }
}
```

## Regras monetárias

O MVP usa `Money` com unidades mínimas inteiras. Exemplo: `10.24 EUR` é
representado como `1024`.

As percentagens são representadas em basis points. Exemplo: `1500` representa
`15%`.

As quantidades usam milésimos para suportar valores como `1.5` ou `2.375`.

Consulte [Regras financeiras](docs/money-rules.md) para detalhes de subtotal,
descontos, impostos, retenções, arredondamento e notas de crédito.

## Segurança

- escape de HTML nos templates oficiais;
- recursos remotos desativados por defeito;
- JavaScript desativado por defeito no Dompdf e no Browsershot;
- storage local com bloqueio básico contra path traversal;
- validação da fatura antes da renderização;
- sem logging automático de dados fiscais;
- templates personalizados devem ser tratados como código confiável.

Consulte [Segurança](docs/security.md).

## Qualidade

```bash
composer validate --no-check-lock --strict
composer cs
composer analyse
composer test
composer rector
```

O CI executa a matriz em Linux e Windows para PHP 8.2, 8.3 e 8.4.

## Documentação

- [Arquitetura](docs/architecture.md)
- [Utilização](docs/usage.md)
- [Regras financeiras](docs/money-rules.md)
- [Segurança](docs/security.md)
- [Compatibilidade](docs/compatibility.md)
- [Changelog](CHANGELOG.md)
- [Análise do repositório de referência](docs/reference-analysis.md)
- [Packagist](docs/packagist.md)
- [Roadmap](docs/roadmap.md)
- [Security Policy](SECURITY.md)
- [Checklist de implementação](docs/implementation-checklist.md)
- [ADR 0001 - Package unico](docs/adr/0001-monorepo.md)
- [ADR 0002 - Dinheiro](docs/adr/0002-money.md)
- [Exemplos completos](examples/README.md)

## Estado do projeto

O projeto está em fase beta e pronto para a primeira release `0.1.0`. O core,
os templates, os exemplos e os bridges iniciais já existem, com CI em Linux e
Windows para PHP 8.2, 8.3 e 8.4.

Antes de declarar estabilidade `1.0`, ainda faltam testes de integração
completos para Laravel, Yii2 e Symfony e snapshots de PDF.

## Roadmap curto

- estabilizar o core `kowts/pdf-invoices`;
- completar testes de contrato para drivers PDF;
- adicionar suites de integração dos bridges;
- publicar o package Composer `kowts/pdf-invoices` no Packagist;
- endurecer politicas de assets para drivers PDF.

## Licença

[MIT](LICENSE) © 2026 Kowts.
