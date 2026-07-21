# ADR 0001 - Package Composer unico

## Estado

Aceite.

## Contexto

A primeira versao foi desenhada como monorepo com packages separados para core
e bridges. Ao submeter ao Packagist, isso fazia a raiz aparecer como
`pdf-invoices/php-monorepo`, porque o Packagist le o `composer.json` da raiz.

O projeto `efaura` usa outro padrao: um package publicavel na raiz, com bridges
opcionais dentro de `src/Bridge/*`.

## Decisao

Publicar este repositorio como package unico `kowts/pdf-invoices`.

O core fica em `src/` e os bridges opcionais ficam em:

- `src/Bridge/Laravel`;
- `src/Bridge/Yii2`;
- `src/Bridge/Symfony`.

## Consequencias

- O Packagist encontra o nome correto diretamente no `composer.json` da raiz.
- A instalacao fica simples: `composer require kowts/pdf-invoices`.
- As integracoes continuam opcionais via `suggest` e dependencias dev.
- Se no futuro for necessario separar packages, os bridges podem ser extraidos
  para repositorios proprios.

