# Regras financeiras

## Estratégia monetária

O MVP usa `Money` com unidades mínimas inteiras. Exemplo: `10.24 EUR` é guardado
como `1024`. A escala vem do código da moeda e pode ser indicada manualmente.

## Alternativas avaliadas

| Alternativa | Vantagens | Desvantagens | Decisão |
| --- | --- | --- | --- |
| Unidades mínimas | Simples, rápido, DB-friendly | Exige cuidado com escalas e arredondamento | Usar no MVP |
| Decimal string | Bom para importação e precisão arbitrária | Mais código aritmético | Futuro para quantidades/importes avançados |
| Value object próprio | API clara | Manutenção interna | Implementado como `Money` |
| `brick/money` | Muito robusto | Dependência extra | Adaptador futuro opcional |

## Regras implementadas

- Subtotal de linha: `unitPrice * quantity`.
- Desconto de linha: aplicado ao subtotal da linha.
- Base tributável de linha: subtotal menos desconto.
- Imposto excluído: aplicado à base tributável.
- Imposto incluído: extraído pela fórmula `amount * rate / (100% + rate)`.
- Desconto global: aplicado à soma das bases tributáveis após descontos de
  linha.
- Retenções: aplicadas à base tributável após desconto global.
- Total: base tributável + imposto - retenção.
- Múltiplas taxas: somadas por linha.
- Valores negativos: bloqueados por defeito, exceto quando a fatura tem
  atributo `credit_note`.

## Decisões futuras

- Arredondamento configurável por linha ou por documento.
- Tabelas fiscais por país.
- Notas de crédito como tipo explícito.
- Suporte formal a moedas e escalas ISO completas.

