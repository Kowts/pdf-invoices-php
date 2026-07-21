# Compatibilidade

Data de verificação: 2026-07-21.

## PHP

O core recomenda PHP `^8.2` para alinhar com Symfony 7.4 LTS e Laravel 12,
mantendo uma base moderna sem exigir PHP 8.4 no MVP.

## Laravel

- Laravel 12 suporta PHP 8.2 a 8.5 e recebe correções de segurança até
  2027-02-24.
- Laravel 13 requer PHP 8.3 a 8.5.
- Bridge recomendado: Laravel `^12.0 || ^13.0`.

## Symfony

- Symfony 6.4 LTS requer PHP 8.1 e recebe segurança até 2027-11.
- Symfony 7.4 LTS requer PHP 8.2 e recebe segurança até 2029-11.
- Symfony 8.x requer PHP 8.4.
- Bridge recomendado: componentes Symfony `^7.4 || ^8.0`.

## Yii2

- Yii 2.0 requer PHP 7.4+ na documentação atual.
- Bridge recomendado: Yii `^2.0.55`, com testes em PHP 8.2 a 8.4 para o
  ecossistema deste projeto.

