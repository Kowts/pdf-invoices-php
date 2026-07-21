# ADR 0001 - Monorepo com packages Composer

## Estado

Aceite.

## Contexto

A biblioteca tem um core independente de frameworks e bridges oficiais para
Laravel, Yii2 e Symfony. O desenvolvimento inicial precisa de alterações
coordenadas entre contratos, adaptadores, exemplos e documentação.

## Decisão

Usar um monorepo com `packages/core`, `packages/bridge-laravel`,
`packages/bridge-yii2` e `packages/bridge-symfony`, cada um publicável como
package Composer independente.

## Consequências

- A evolução dos contratos do core fica sincronizada com os bridges.
- O CI pode testar a matriz completa num único pull request.
- A publicação exige disciplina de versionamento por package.
- Se os bridges ganharem equipas ou ciclos próprios, podem ser extraídos mais
  tarde para repositórios separados sem alterar os namespaces públicos.

