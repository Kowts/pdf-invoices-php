# Exemplos

Esta pasta contem exemplos pequenos para validar a API publica e servir como
ponto de partida para aplicacoes reais.

## PHP puro

- [shared_invoice.php](plain-php/shared_invoice.php): cria uma fatura de exemplo
  reutilizavel pelos restantes scripts.
- [html-preview.php](plain-php/html-preview.php): gera HTML com o renderer de
  preview.
- [dompdf-pdf.php](plain-php/dompdf-pdf.php): gera PDF real com Dompdf.
- [tax-included.php](plain-php/tax-included.php): demonstra imposto incluido no
  preco.
- [credit-note.php](plain-php/credit-note.php): demonstra uma nota de credito
  com valores negativos permitidos.
- [generate.php](plain-php/generate.php): exemplo inicial mantido por
  compatibilidade.

## Frameworks

- [Laravel controller](laravel/InvoiceController.php)
- [Yii2 config](yii2/config-web.php)
- [Yii2 controller](yii2/InvoiceController.php)
- [Symfony controller](symfony/InvoiceController.php)

## Executar

Depois de instalar as dependencias do package:

```bash
php examples/plain-php/html-preview.php
php examples/plain-php/tax-included.php
php examples/plain-php/credit-note.php
```

Para PDF real:

```bash
composer require dompdf/dompdf
php examples/plain-php/dompdf-pdf.php
```

Os ficheiros gerados ficam em `build/`.
