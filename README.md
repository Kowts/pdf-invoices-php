# pdf-invoices-php

Biblioteca PHP para gerar faturas PDF com core independente de frameworks e
bridges oficiais para Laravel, Yii2 e Symfony.

## Estado

Projeto em implementação inicial. O MVP privilegia:

- core PHP puro;
- cálculos monetários sem `float`;
- templates PHP nativos;
- Dompdf como driver opcional;
- armazenamento local;
- exemplos sem framework.

## Packages

- `pdf-invoices/core`
- `pdf-invoices/laravel`
- `pdf-invoices/yii2`
- `pdf-invoices/symfony`

## Arquitetura

```text
Frameworks
    ↓
Bridges e adaptadores
    ↓
pdf-invoices-php Core
    ↓
PHP e contratos independentes
```

O core não depende de Laravel, Yii2, Symfony, Carbon, Twig, Blade, Eloquent,
Active Record, facades, helpers globais ou containers específicos.

