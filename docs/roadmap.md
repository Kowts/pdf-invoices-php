# Roadmap

## Fase 0 - Decisões arquiteturais

- Objetivo: ADRs, licença, namespaces, package Composer e compatibilidade.
- Estado: concluído.
- Critérios: `composer.json`, ADRs, CI, NOTICE, `.gitignore`.
- Dimensão: pequena.

## Fase 1 - Core mínimo

- Objetivo: domínio, value objects, builders, cálculo e validação.
- Estado: concluído.
- Riscos: arredondamento fiscal por jurisdição.
- Testes: unitários de `Money`, `Quantity`, `InvoiceCalculator`.
- Dimensão: média.

## Fase 2 - Templates e Dompdf

- Objetivo: HTML via PHP templates e engine Dompdf opcional.
- Estado: concluído para MVP.
- Riscos: fidelidade visual e fontes.
- Testes: snapshot HTML e contrato de engine.
- Dimensão: média.

## Fase 3 - Localização e armazenamento

- Objetivo: traduções `en`/`pt_PT`, formatter simples e storage local.
- Estado: concluído para MVP.
- Dimensão: pequena.

## Fase 4 - Bridge Laravel

- Objetivo: provider, bindings, config, facade opcional, translator, storage e response.
- Estado: esqueleto funcional.
- Testes futuros: Orchestra Testbench Laravel 12/13.
- Dimensão: média.

## Fase 5 - Bridge Yii2

- Objetivo: componente configurável, bootstrap, tradução e response.
- Estado: esqueleto funcional.
- Testes futuros: app Yii2 web e console.
- Dimensão: média.

## Fase 6 - Bridge Symfony

- Objetivo: bundle, extension DI, translator e response.
- Estado: esqueleto funcional.
- Testes futuros: KernelTestCase Symfony 7.4/8.
- Dimensão: média.

## Fase 7 - Drivers adicionais

- Estado: concluido para adapters iniciais.
- Dompdf hardening.
- mPDF adapter.
- TCPDF adapter.
- Browsershot/Chromium adapter com JavaScript desativado por defeito.
- Proximo passo: allowlist de assets e snapshots PDF por driver.
- Dimensão: grande.

## Fase 8 - Documentação, hardening e lançamento

- Guia de instalação do package.
- Exemplos completos por framework.
- Snapshots HTML/PDF.
- Mutation testing opcional.
- Release `0.1.0`.
- Dimensão: grande.

## MVP

Incluído:

- PHP puro;
- API fluente;
- cálculos monetários seguros sem `float` como representação principal;
- templates `minimal`, `modern`, `branded`;
- inglês e português;
- storage local;
- geração HTML preview e contrato PDF;
- Dompdf, mPDF, TCPDF e Browsershot opcionais;
- testes unitários preparados;
- exemplo funcional sem framework.

Fora do MVP:

- Blade/Twig completos;
- QR Code;
- filas, mailables e commands;
- painel de configuração visual;
- snapshot PDF binário estável.
