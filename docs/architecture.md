# Arquitetura proposta

## Resumo executivo

`pdf-invoices-php` é organizado como monorepo Composer com um core PHP puro e
bridges oficiais para Laravel, Yii2 e Symfony. O core contém domínio, cálculos,
contratos, templates PHP nativos, tradução básica, storage local e geração de
documentos. Os bridges só fazem integração com containers, tradutores, responses
e filesystems de cada framework.

## Diagrama de dependências

```text
Aplicações Laravel / Yii2 / Symfony / PHP puro
    ↓
Bridges: pdf-invoices/laravel, pdf-invoices/yii2, pdf-invoices/symfony
    ↓
Contratos do core: renderer, engine PDF, storage, tradutor, formatter
    ↓
Domínio: Invoice, InvoiceItem, Party, Address, Money, Percentage, Quantity
    ↓
PHP 8.2+, sem framework
```

## Estrutura de packages

```text
packages/
├── core/
│   ├── src/
│   │   ├── Builder/
│   │   ├── Calculation/
│   │   ├── Contract/
│   │   ├── Domain/
│   │   ├── Exception/
│   │   ├── Formatting/
│   │   ├── Localization/
│   │   ├── Pdf/
│   │   ├── Storage/
│   │   ├── Support/
│   │   ├── Template/
│   │   └── Validation/
│   ├── resources/
│   │   ├── templates/
│   │   └── translations/
│   └── tests/
├── bridge-laravel/
├── bridge-yii2/
└── bridge-symfony/
```

## Contratos principais

- `PdfEngineInterface`: recebe HTML e `PdfOptions`, devolve
  `GeneratedDocument`.
- `TemplateRendererInterface`: transforma um template e `TemplateContext` em
  HTML.
- `TemplateResolverInterface`: resolve nomes de templates para ficheiros.
- `StorageInterface`: grava, lê, verifica e apaga documentos.
- `TranslatorInterface`: traduz labels sem acoplar ao mecanismo do framework.
- `CurrencyFormatterInterface`: formata `Money`.
- `InvoiceValidatorInterface`: valida faturas antes de gerar output.
- `AssetResolverInterface`: reservado para política segura de assets.

## Matriz manter/adaptar/reescrever/remover

| Área | Decisão | Implementação atual |
| --- | --- | --- |
| Builders | Adaptar | Builders fluentes no core, sem Laravel/Carbon. |
| DTOs | Adaptar | Objetos de domínio imutáveis. |
| Cálculos | Reescrever | `InvoiceCalculator` com `Money` inteiro. |
| Templates | Adaptar | PHP nativo no core; Blade/Twig ficam opcionais. |
| Dompdf | Tornar opcional | `DompdfEngine` sem dependência obrigatória. |
| Browsershot | Futuro opcional | Não entra no MVP por peso e superfície de risco. |
| Storage Laravel | Mover | `LaravelStorage` fica no bridge. |
| Tradução Laravel | Mover | `LaravelTranslator` fica no bridge. |
| Facade | Opcional | Incluída no bridge Laravel, API principal usa DI. |
| Commands | Futuro | Não é necessário no MVP. |

