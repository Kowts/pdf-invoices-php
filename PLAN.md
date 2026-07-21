Atua como arquiteto de software PHP sénior e especialista em criação de packages Composer reutilizáveis.

## Objetivo

Analisa integralmente o repositório:

https://github.com/akira-io/laravel-pdf-invoices

Com base nessa análise, cria um plano técnico detalhado para desenvolver uma nova biblioteca chamada:

`pdf-invoices-php`

A nova biblioteca deve ter um núcleo em PHP puro, completamente independente de frameworks, acompanhado por bridges oficiais para:

* Yii2;
* Laravel;
* Symfony.

Não deves implementar o projeto completo nesta fase. O resultado principal deve ser um plano de arquitetura e implementação suficientemente detalhado para orientar o desenvolvimento posterior.

## 1. Análise obrigatória do repositório de referência

Não te limites ao `README.md`.

Analisa, pelo menos:

* `composer.json`;
* estrutura da pasta `src`;
* builders;
* DTOs;
* contratos e interfaces;
* drivers de geração de PDF;
* gestão de templates;
* localização e traduções;
* armazenamento;
* configuração;
* Service Provider do Laravel;
* commands;
* testes;
* documentação;
* roadmap;
* workflows de CI/CD;
* ferramentas de qualidade de código.

Identifica claramente:

1. Funcionalidades existentes;
2. Componentes reutilizáveis conceptualmente;
3. Dependências específicas do Laravel;
4. Uso de facades, helpers globais e container do Laravel;
5. Dependências de Carbon;
6. Dependências dos drivers de PDF;
7. Responsabilidades atualmente misturadas;
8. Limitações técnicas;
9. Riscos de segurança;
10. Oportunidades de melhoria.

Cria uma matriz com:

* manter;
* adaptar;
* reescrever;
* remover;
* tornar opcional.

Não copies cegamente a arquitetura original. Usa-a apenas como referência e respeita a licença MIT, incluindo os créditos e avisos necessários.

## 2. Arquitetura pretendida

A arquitetura deve seguir o princípio:

```text
Frameworks
    ↓
Bridges e Adaptadores
    ↓
pdf-invoices-php Core
    ↓
PHP e contratos independentes
```

O núcleo nunca pode depender de:

* Laravel;
* Yii2;
* Symfony;
* facades;
* helpers de frameworks;
* containers específicos;
* Eloquent;
* Active Record;
* Twig;
* Blade;
* Carbon;
* filesystem específico de frameworks.

Os bridges podem depender do respetivo framework, mas o core não pode conhecer os bridges.

Avalia e recomenda uma organização em monorepo semelhante a:

```text
pdf-invoices-php/
├── packages/
│   ├── core/
│   ├── bridge-laravel/
│   ├── bridge-yii2/
│   └── bridge-symfony/
├── examples/
│   ├── plain-php/
│   ├── laravel/
│   ├── yii2/
│   └── symfony/
├── docs/
├── tests/
├── AGENTS.md
├── composer.json
└── README.md
```

Compara esta opção com a utilização de repositórios Composer separados e apresenta uma recomendação justificada.

## 3. Componentes do core

Propõe contratos independentes para componentes como:

```text
Invoice
InvoiceItem
Party
Address
Tax
Discount
Money
InvoiceBuilder
ItemBuilder
PartyBuilder

PdfEngineInterface
TemplateRendererInterface
TemplateResolverInterface
StorageInterface
TranslatorInterface
CurrencyFormatterInterface
AssetResolverInterface
InvoiceValidatorInterface
```

Avalia também a necessidade de:

* `Configuration`;
* `PdfOptions`;
* `TemplateContext`;
* `GeneratedDocument`;
* `InvoiceCalculator`;
* `InvoiceNumber`;
* `TaxRate`;
* `DiscountRate`;
* eventos ou hooks;
* metadata e custom attributes.

Explica a responsabilidade de cada componente e as dependências permitidas.

## 4. Regras de domínio financeiro

Não referenciar "akira-io/laravel-pdf-invoices" no projeto.

Documentação em PT-pt.

Não utilizes `float` como representação principal de valores monetários.

Apresenta uma estratégia segura baseada numa destas opções:

* valores em unidades mínimas, como cêntimos;
* decimal representado por string;
* value object próprio;
* biblioteca especializada como `brick/money`.

Compara as opções e recomenda a mais adequada considerando:

* precisão;
* simplicidade;
* número de dependências;
* moedas sem casas decimais;
* arredondamento;
* impostos;
* descontos;
* quantidades fracionadas;
* compatibilidade com bases de dados.

Define regras claras para:

* subtotal;
* desconto por linha;
* desconto global;
* imposto por linha;
* imposto incluído ou excluído;
* arredondamento;
* total final;
* múltiplas taxas;
* retenções;
* valores negativos;
* notas de crédito.

## 5. Motores de PDF

O core deve trabalhar através de `PdfEngineInterface`.

Analisa e propõe adaptadores opcionais para:

* Dompdf;
* mPDF;
* TCPDF;
* Browsershot/Chromium;
* outros motores relevantes.

O Dompdf pode ser o driver inicial do MVP, mas não deve ficar acoplado ao domínio.

Define como cada driver deverá:

* receber HTML e opções;
* gerar conteúdo binário;
* guardar ficheiros;
* devolver stream ou conteúdo;
* configurar formato, orientação e margens;
* gerir fontes e imagens;
* tratar ficheiros temporários;
* reportar erros através de exceções próprias.

## 6. Templates

Define uma estratégia de templates independente de frameworks.

Considera:

* templates PHP nativos como opção base;
* Twig como adaptador opcional;
* Blade apenas no bridge Laravel;
* Twig integrado no bridge Symfony;
* mecanismo próprio ou PHP views no bridge Yii2.

O core deve incluir inicialmente templates equivalentes a:

* minimal;
* modern;
* branded.

Explica como permitir:

* templates personalizados;
* sobrescrita de templates;
* temas;
* logótipo;
* cores;
* CSS personalizado;
* cabeçalho e rodapé;
* paginação;
* fontes;
* traduções;
* campos adicionais;
* QR Code opcional.

## 7. Bridge Laravel

Planeia um package semelhante a:

`pdf-invoices-laravel`

Deve incluir apenas as integrações Laravel:

* Service Provider;
* publicação de configuração;
* publicação de templates;
* bindings no container;
* facade opcional;
* Laravel Storage adapter;
* Laravel Translator adapter;
* Blade renderer opcional;
* helpers para `Response`;
* integração opcional com queues e mailables.

Mantém a API principal disponível sem facade.

## 8. Bridge Yii2

Planeia um package semelhante a:

`pdf-invoices-yii2`

Deve incluir:

* componente Yii2 configurável;
* integração com o DI container;
* implementação opcional de `BootstrapInterface`;
* aliases para templates e armazenamento;
* integração com `Yii::t`;
* adapter de filesystem;
* geração de `yii\web\Response`;
* configuração através de `components`;
* possibilidade de utilização em aplicações web e console.

Apresenta um exemplo mínimo de configuração no `config/web.php`.

## 9. Bridge Symfony

Planeia um package semelhante a:

`pdf-invoices-symfony`

Deve incluir:

* Symfony Bundle;
* Dependency Injection Extension;
* configuração semântica;
* `services.yaml`;
* autowiring e autoconfiguration;
* Twig adapter opcional;
* Translator adapter;
* Filesystem adapter;
* resposta para download;
* commands opcionais;
* configuração por ambiente.

Apresenta um exemplo mínimo de configuração em YAML.

## 10. Experiência de utilização

Propõe uma API fluente, mas independente de frameworks.

Apresenta exemplos pequenos para:

### PHP puro

```php
$invoice = InvoiceBuilder::create()
    ->seller(...)
    ->buyer(...)
    ->addItem(...)
    ->currency('CVE')
    ->build();

$document = $generator->generate($invoice);
$document->save('/path/invoice.pdf');
```

### Laravel

Mostra a resolução através do container, sem tornar a facade obrigatória.

### Yii2

Mostra a utilização através de um componente configurado.

### Symfony

Mostra a injeção de dependências num controller ou service.

Os exemplos servem apenas para validar a arquitetura. Não implementes todas as classes.

## 11. Segurança

Inclui um capítulo específico sobre:

* escape de HTML;
* templates fornecidos pelo utilizador;
* SSRF através de imagens remotas;
* acesso a ficheiros locais;
* path traversal;
* execução de JavaScript;
* permissões de escrita;
* diretórios temporários;
* limpeza de ficheiros;
* limites de memória;
* tamanho máximo de imagens;
* proteção contra XML ou SVG malicioso;
* carregamento seguro de fontes;
* logging sem exposição de dados fiscais;
* validação dos dados da fatura.

Por padrão, recursos remotos devem estar desativados ou sujeitos a allowlist.

## 12. Compatibilidade e qualidade

Propõe:

* versão mínima de PHP;
* estratégia de versionamento semântico;
* namespaces PSR-4;
* PSR-12;
* PHPUnit;
* PHPStan no nível máximo possível;
* Rector;
* PHP-CS-Fixer ou ferramenta equivalente;
* mutation testing opcional;
* GitHub Actions;
* testes em Windows e Linux;
* matriz de versões PHP;
* matriz de versões Yii2, Laravel e Symfony;
* cobertura de testes;
* testes de snapshot dos PDFs ou HTML;
* testes de contrato para todos os drivers.

Verifica as versões atualmente suportadas dos frameworks nas respetivas documentações oficiais antes de definir a matriz.

## 13. Estrutura de pastas

Apresenta uma árvore completa recomendada para o projeto, incluindo:

* domínio;
* builders;
* contratos;
* value objects;
* exceções;
* engines;
* renderers;
* templates;
* translations;
* storage;
* bridges;
* testes;
* fixtures;
* exemplos;
* documentação;
* configuração de qualidade;
* CI/CD.

Inclui obrigatoriamente:

```text
AGENTS.md
```

Este ficheiro deve registar intervenções técnicas realizadas por agentes ou IA.

Inclui também o `AGENTS.md` no `.gitignore`, conforme o padrão definido para o projeto.

## 14. Roadmap

Divide a implementação em fases:

### Fase 0 — Decisões arquiteturais

Inclui ADRs, licença, namespaces, packages e versões mínimas.

### Fase 1 — Core mínimo

Inclui entidades, value objects, builders, cálculos e validação.

### Fase 2 — Templates e Dompdf

Inclui renderização HTML, templates iniciais e primeiro driver funcional.

### Fase 3 — Localização e armazenamento

Inclui traduções, formatação monetária e storage local.

### Fase 4 — Bridge Laravel

### Fase 5 — Bridge Yii2

### Fase 6 — Bridge Symfony

### Fase 7 — Drivers adicionais

### Fase 8 — Documentação, hardening e lançamento

Para cada fase apresenta:

* objetivo;
* tarefas;
* ficheiros ou módulos envolvidos;
* dependências;
* riscos;
* critérios de aceitação;
* testes necessários;
* dimensão estimada: pequena, média ou grande.

## 15. MVP

Define claramente o MVP.

O MVP deve privilegiar:

* PHP puro;
* uma API estável;
* cálculos monetários seguros;
* Dompdf;
* templates PHP;
* inglês e português;
* armazenamento local;
* geração, download e gravação do PDF;
* testes unitários;
* documentação de instalação;
* um exemplo funcional sem framework.

Separa as funcionalidades do MVP das funcionalidades futuras.

## 16. Formato obrigatório da resposta

Entrega a resposta nesta ordem:

1. Resumo executivo;
2. Análise do repositório original;
3. Problemas de acoplamento identificados;
4. Funcionalidades a manter, adaptar ou remover;
5. Arquitetura proposta;
6. Diagrama de dependências;
7. Estrutura de packages e pastas;
8. Contratos e classes principais;
9. Estratégia monetária;
10. Estratégia de templates;
11. Estratégia de drivers PDF;
12. Bridge Laravel;
13. Bridge Yii2;
14. Bridge Symfony;
15. Segurança;
16. Estratégia de testes;
17. Roadmap por fases;
18. Definição do MVP;
19. Riscos e decisões pendentes;
20. Ordem recomendada de implementação;
21. Checklist final de preparação do repositório.

Não forneças apenas recomendações genéricas. Relaciona cada decisão com elementos concretos encontrados no repositório analisado.

Quando uma decisão tiver mais de uma alternativa válida, apresenta:

* alternativa A;
* alternativa B;
* vantagens;
* desvantagens;
* recomendação final.

No final, apresenta um plano que outra AI ou programador possa seguir passo a passo para criar o projeto sem depender de informações adicionais.
