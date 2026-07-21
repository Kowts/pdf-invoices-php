# Análise do repositório de referência

Data da análise: 2026-07-21.

## Funcionalidades encontradas

- API fluente com builders para fatura, entidade e item.
- DTOs imutáveis para fatura, entidade e item.
- Três templates: minimal, modern e branded.
- Traduções em `en`, `es`, `fr`, `it`, `pt_BR` e `pt_PT`.
- Drivers PDF via Dompdf e Spatie/Browsershot.
- Service Provider Laravel, facade, config publicável e command Artisan.
- Storage adapter baseado no filesystem do Laravel.
- Testes Pest para builders, cálculos, formatadores, storage, facade,
  provider e drivers.
- Ferramentas de qualidade: PHPStan, Pint, Rector, Pest Arch, Peck e CI em
  GitHub Actions.

## Componentes conceptualmente reutilizáveis

- Separação entre builders e dados finais.
- Templates nomeados.
- Contratos para geração PDF, storage e formatação de moeda.
- A ideia de atributos customizados por fatura, entidade e item.
- Documentação modular por instalação, configuração, builders, templates,
  localização, storage e troubleshooting.

## Acoplamentos e limitações

- O package exige Laravel e `illuminate/contracts`.
- O Service Provider usa `spatie/laravel-package-tools`.
- Os drivers chamam facades Laravel diretamente.
- Templates usam Blade e namespaces de view Laravel.
- Tradução depende de `trans()`.
- Configuração depende de `config()`.
- Datas aceitam Carbon e convertem com facade `Date`.
- Storage depende de `Illuminate\Contracts\Filesystem\Filesystem`.
- Cálculos financeiros usam `float`.
- Quantidade é `int`, limitando casos com quantidades fracionadas.
- `generatePdf()` no modelo de dados mistura domínio e infraestrutura.
- Browsershot/Chromium entra como dependência principal, apesar de ser pesado.
- Recursos remotos, imagens, fontes e JavaScript precisam de política explícita
  de segurança por driver.

## Matriz de decisão

| Elemento | Decisão | Justificação |
| --- | --- | --- |
| Builders fluentes | Adaptar | Boa experiência, mas devem construir objetos de core puros. |
| DTOs readonly | Adaptar | Manter imutabilidade, removendo Carbon, Laravel e floats. |
| Cálculos no DTO | Reescrever | Criar `InvoiceCalculator` testável com dinheiro inteiro. |
| Dompdf | Tornar opcional | Útil para MVP, mas atrás de `PdfEngineInterface`. |
| Browsershot | Tornar opcional | Excelente fidelidade, mas pesado e com riscos de JS/SSRF. |
| Blade | Mover para bridge | Blade é integração Laravel, não core. |
| Traduções Laravel | Mover para bridge | Core deve ter `TranslatorInterface`. |
| Storage Laravel | Mover para bridge | Core deve ter storage local e contrato simples. |
| Service Provider | Reescrever no bridge | Deve depender do package core publicado. |
| Facade | Tornar opcional | API principal deve funcionar via DI/container. |
| Helpers globais | Remover do core | `throw_if`, `view`, `config`, `trans` são Laravel. |
| PHPStan/Pint/Rector/CI | Adaptar | Manter qualidade, com ferramentas independentes. |

