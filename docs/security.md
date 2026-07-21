# Segurança

## Políticas por defeito

- Templates PHP nativos fazem escape com `htmlspecialchars`.
- Recursos remotos estão desativados por defeito em `PdfOptions`.
- JavaScript fica desativado no `DompdfEngine`.
- Storage local bloqueia `../` e bytes nulos para reduzir path traversal.
- O core valida vendedor, cliente, itens e moedas antes da geração.

## Riscos e mitigação

| Risco | Mitigação |
| --- | --- |
| HTML não escapado | Usar `Escaper::html` em templates oficiais. |
| Templates fornecidos pelo utilizador | Tratar como código confiável ou executar em ambiente isolado. |
| SSRF por imagens remotas | `allowRemoteResources=false` por defeito; futura allowlist. |
| Ficheiros locais indevidos | `Dompdf` com `chroot`; storage com base path controlado. |
| Path traversal | Bloqueio de `../` e resolução dentro do diretório base. |
| JavaScript | Desativado no Dompdf; Browsershot futuro deve ter política explícita. |
| Fontes e SVG maliciosos | Permitir apenas assets locais validados ou allowlist. |
| Excesso de memória | Definir limites por driver e tamanho máximo de imagens. |
| Dados fiscais em logs | Nunca registar HTML completo nem payloads fiscais por defeito. |
| Faturas inválidas | `InvoiceValidatorInterface` antes da renderização. |

## Pendências

- `AssetResolverInterface` com allowlist, limite de tamanho e validação MIME.
- Limpeza explícita de ficheiros temporários por driver.
- Testes com payloads SVG/XML maliciosos.

