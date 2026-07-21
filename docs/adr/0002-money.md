# ADR 0002 - Dinheiro e cálculos em unidades mínimas

## Estado

Aceite para o MVP.

## Contexto

Faturas exigem cálculos previsíveis de subtotal, descontos, impostos,
retenções e totais. `float` não é seguro como representação principal de
valores monetários.

## Decisão

O MVP usa um `Money` value object baseado em unidades mínimas inteiras e código
ISO 4217. Percentagens usam basis points, onde `10000` representa `100%`.

## Alternativas

- Decimal como string: flexível para quantidades e importes externos, mas exige
  mais código de aritmética.
- `brick/money`: muito robusto, mas aumenta dependências e decisões de contexto
  monetário no MVP.

## Consequências

- Integra bem com bases de dados que armazenam cêntimos ou unidades mínimas.
- Suporta moedas sem casas decimais através da escala da moeda.
- Quantidades fracionadas são representadas separadamente em milésimos.
- A biblioteca pode adicionar um adaptador `brick/money` mais tarde sem mudar
  a API principal.

