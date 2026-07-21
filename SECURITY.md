# Security Policy

## Versoes suportadas

| Versao | Suporte |
| --- | --- |
| `0.1.x` | Correcoes de seguranca enquanto a serie beta estiver ativa |

## Reportar vulnerabilidades

Nao abra issues publicas para vulnerabilidades.

Reporte o problema atraves dos canais de contacto do mantenedor no GitHub:

- https://github.com/Kowts

Inclua, quando possivel:

- versao afetada;
- descricao do impacto;
- passos minimos para reproduzir;
- exemplo de entrada maliciosa, sem dados reais de clientes.

## Modelo de seguranca

Este package gera HTML/PDF a partir de dados de fatura. Templates oficiais fazem
escape de HTML, recursos remotos estao desativados por defeito no Dompdf e o
storage local valida caminhos para reduzir risco de path traversal.

Templates personalizados devem ser tratados como codigo confiavel.
