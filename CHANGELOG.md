# Changelog

Todas as alteracoes relevantes deste projeto serao documentadas neste ficheiro.

O formato segue [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) e o
projeto usa [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Adicionado

- Resolver para diretorios de templates customizados com fallback para os
  templates oficiais.
- Drivers PDF opcionais para mPDF, TCPDF e Browsershot.
- Exemplos plain PHP para templates customizados e drivers PDF alternativos.
- Testes de contrato para mPDF e TCPDF.
- Suites de integracao iniciais para os bridges Laravel, Symfony e Yii2.

## [0.1.0] - 2026-07-21

### Adicionado

- Core PHP puro para construir faturas, linhas, entidades, moradas, totais,
  descontos, impostos e retencoes.
- Templates PHP nativos `minimal`, `modern` e `branded`.
- Preview HTML e contrato `PdfEngineInterface`.
- Engine Dompdf opcional com recursos remotos e JavaScript desativados por
  defeito.
- Storage local com protecao basica contra path traversal.
- Bridges iniciais para Laravel, Yii2 e Symfony.
- Exemplos plain PHP e exemplos de integracao por framework.
- Testes PHPUnit, PHPStan level 8, PHP-CS-Fixer, Rector e CI em Linux/Windows
  para PHP 8.2, 8.3 e 8.4.

### Notas

- Primeira release beta publica.
- Os bridges de framework sao funcionais, mas ainda precisam de suites de
  integracao dedicadas em aplicacoes reais.
