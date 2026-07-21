# Packagist

## Atualizacao automatica

O Packagist deve ser atualizado automaticamente quando houver `push` para o
GitHub. A forma recomendada e ativar o GitHub Hook no proprio Packagist.

## Opcao recomendada

1. Entrar no Packagist usando GitHub.
2. Garantir que a aplicacao Packagist tem acesso a organizacao `Kowts`.
3. Abrir a lista de packages no Packagist.
4. Usar a sincronizacao da conta ou a opcao indicada pelo aviso do package.
5. Confirmar que o aviso "This package is not auto-updated" desapareceu.

## Webhook manual no GitHub

Se nao quiser autorizar a aplicacao Packagist a configurar webhooks, crie um
webhook manual no repositorio GitHub:

- Payload URL: `https://packagist.org/api/github?username=PACKAGIST_USERNAME`
- Content type: `application/json`
- Secret: token API do Packagist
- Events: apenas `push`

## Fallback por GitHub Actions

Este repositorio tambem inclui `.github/workflows/packagist.yml`, que pode
chamar a API generica do Packagist depois de pushes para `main` ou tags.

Configure no GitHub:

- Secret `PACKAGIST_USERNAME`: utilizador Packagist.
- Secret `PACKAGIST_API_TOKEN`: API token do Packagist.
- Variable `PACKAGIST_PACKAGE_URL`: URL do package no Packagist.

Exemplo de `PACKAGIST_PACKAGE_URL`:

```text
https://packagist.org/packages/kowts/pdf-invoices
```

Esta workflow e um fallback. Se o GitHub Hook do Packagist estiver ativo, ela
pode ser removida ou deixada sem secrets/vars.

