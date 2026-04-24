# Plano de E-commerce de Licenças de Software
## Baseado no ERP existente (`ecommerce-erp`)

> **Inspiração:** KR Softs (https://krsofts.com.br/)  
> **Produto exemplo:** SketchUp Pro 2024  
> **Data de análise:** Abril 2026

---

## 1. Visão Geral da Arquitetura Sugerida

```
┌─────────────────────────────────────────────────────────────────┐
│                         CLIENTE FINAL                           │
│  Navega produtos → Compra → Fornece ID do PC → Recebe Licença   │
└──────────────────────────┬──────────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────────┐
│                     FRONTEND (Site Público)                     │
│  PHP/HTML/CSS/JS  │  Páginas de produto, checkout, área cliente │
└──────────────────────────┬──────────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────────┐
│                  BACKEND (ERP Adaptado - PHP)                   │
│                                                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌───────────────────────┐ │
│  │  E-commerce  │  │ Licenciamento│  │    Agendamento        │ │
│  │  (existente) │  │  (NOVO)      │  │    (NOVO)             │ │
│  └──────────────┘  └──────────────┘  └───────────────────────┘ │
│                                                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌───────────────────────┐ │
│  │  Pagamentos  │  │   Emails     │  │    Painel Admin       │ │
│  │  (existente) │  │  (existente) │  │    (adaptado)         │ │
│  └──────────────┘  └──────────────┘  └───────────────────────┘ │
└──────────────────────────┬──────────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────────┐
│                  BANCO DE DADOS (MySQL)                         │
│  Tabelas existentes + Novas: licencas, agendamentos, softwares  │
└─────────────────────────────────────────────────────────────────┘
```

---

## 2. Análise Detalhada do ERP Existente

### 2.1 Tecnologias Identificadas

| Componente       | Tecnologia                          |
|------------------|-------------------------------------|
| Linguagem backend | PHP >= 7.4                         |
| Banco de dados   | MySQL (Doctrine DBAL 3.4.4)         |
| Framework interno | Krypitonite (framework próprio MVC)|
| Email            | PHPMailer ~5.2                      |
| HTTP Client      | Guzzle HTTP                         |
| Logging          | Monolog 1.25.3                      |
| Containerização  | Docker + Docker Compose             |

### 2.2 Padrão Arquitetural

O projeto usa um padrão **MVC customizado** com roteamento por query string:

```
/?m=MODULE&c=CONTROLLER&a=ACTION
```

Exemplo: `/?m=sistema&c=painel` → `Store\Sistema\Controller\PainelController`

**Namespace raiz:** `Store\` (PSR-4, mapeado em `src/`)

### 2.3 Estrutura de Módulos Existentes

```
src/
├── Checkout/           ← Fluxo de compra (carrinho → finalizar pedido)
├── Cliente/            ← Cadastro e gerência de clientes
├── Comentario/         ← Avaliações de produtos
├── Core/               ← DAOs e Models base (toda entidade do banco)
│   ├── Dao/            ← 33 DAOs mapeados (Produto, Pedido, Cliente...)
│   └── Model/          ← 33 Models correspondentes
├── Database/           ← Conexão e abstração do banco
├── Email/              ← Templates e envio de emails
├── Endereco/           ← CEP, endereços de entrega
├── Frete/              ← Integração Correios
├── Pagamento/          ← MercadoPago, Pagarme, Rede, PagSeguro, Upnid
├── Produto/            ← Catálogo de produtos
├── Sistema/            ← Painel Admin (Login, Painel, Vendas, Produtos...)
└── Site/               ← Frontend público do e-commerce
```

### 2.4 Gateways de Pagamento Já Integrados

- **MercadoPago** (`PagamentoMPController`) — cartão + Pix
- **Pagarme** (`PagamentoPagarmeController`) — cartão
- **Cielo/Rede** (`PagamentoRedeController`) — cartão
- **PagSeguro** (`PagamentoService/PagSeguroService`) — legacy
- **Upnid** (`PagamentoUpnidController`) — link de pagamento externo

### 2.5 Painel Administrativo Existente (`src/Sistema/`)

Controllers disponíveis:
- `LoginController` — Autenticação admin (MD5, necessita upgrade)
- `PainelController` — Dashboard com filtros por status, data, produto
- `VendaController` — Gestão de pedidos e expedição
- `ProdutoController` — CRUD de produtos
- `CategoriaController` — Categorias
- `ClienteController` — Gerência de clientes
- `LancamentoController` — Financeiro
- `NfController` — Nota fiscal (NFe integrado)
- `CorreiosController` — Rastreamento e etiquetas
- `PlataformaController` — Configurações gerais da loja

---

## 3. O que Pode Ser Reaproveitado

### ✅ REUTILIZAR DIRETAMENTE (sem modificações)

| Componente | Arquivo / Módulo | Motivo |
|---|---|---|
| Framework MVC | `krypitonite/` | Roteamento, controllers base, views |
| Autenticação Admin | `src/Sistema/Controller/LoginController.php` | Apenas trocar MD5 por password_hash |
| Painel Admin | `src/Sistema/Controller/PainelController.php` | Dashboard de vendas já funcional |
| DAOs / Models | `src/Core/Dao/*.php`, `src/Core/Model/*.php` | Abstração de banco pronta |
| Clientes | `src/Cliente/`, `src/Core/Dao/ClienteCoreDAO.php` | Cadastro e login de clientes |
| Pedidos | `src/Core/Dao/PedidoCoreDAO.php` | Estrutura de pedidos |
| Itens do Pedido | `src/Core/Dao/ItemPedidoCoreDAO.php` | Itens por pedido |
| Email | `src/Email/`, `krypitonite/src/Mail/Email.php` | PHPMailer configurado |
| Pagamentos | `src/Pagamento/Controller/PagamentoMPController.php` | MercadoPago (Pix + cartão) |
| Configurações | `config/Configuration.php` | Config centralizada |
| Sitemap / SEO | `sitemap.xml`, `robots.txt` | Base de SEO |
| Docker | `Dockerfile`, `docker-compose.yml` | Infraestrutura pronta |

### ⚙️ REUTILIZAR COM ADAPTAÇÕES

| Componente | Adaptação Necessária |
|---|---|
| `ProdutoController` (admin) | Adicionar campo `tipo = 'software'`, sem estoque físico, sem frete |
| `CheckoutController` | Remover lógica de frete/endereço físico; adicionar captura de ID do PC |
| `VendaController` (admin) | Adicionar coluna de licença gerada e status de instalação agendada |
| `PainelController` | Adicionar widgets de licenças ativas, agendamentos pendentes |
| `ProdutoCoreDAO` | Adicionar campos: `id_computador_obrigatorio`, `validade_dias`, `max_instalacoes` |
| `PedidoCoreDAO` | Adicionar campo `id_computador_cliente` |
| Frontend `Site/` | Redesign para estilo KR Softs (darkmode tech, CTAs claros) |

### ❌ CRIAR DO ZERO

| Componente | Descrição |
|---|---|
| `src/Licenca/` | Módulo completo de geração e validação de licenças |
| `src/Agendamento/` | Módulo de agendamento de instalação remota |
| `src/Software/` | Módulo para gerenciar softwares e suas versões |
| Script de captura de ID do PC | JS/PHP para coletar Hardware ID do cliente |
| API de validação de licença | Endpoint REST para o software validar a chave |
| Área do cliente (frontend) | Página `/minha-conta` com licenças, downloads, agendamentos |

---

## 4. Diagrama de Módulos do Novo Sistema

```
┌─────────────────────────────────────────────────────────────────────┐
│                        MÓDULOS DO SISTEMA                           │
│                                                                     │
│  ┌─────────────┐   ┌──────────────┐   ┌────────────────────────┐   │
│  │   CLIENTE   │   │   PRODUTO    │   │       SOFTWARE         │   │
│  │             │   │  (adaptado)  │   │       (NOVO)           │   │
│  │ Cadastro    │   │              │   │                        │   │
│  │ Login       │   │ Nome         │   │ Nome do Software       │   │
│  │ Endereço    │   │ Descrição    │   │ Versão                 │   │
│  │ Histórico   │   │ Preço        │   │ Fabricante             │   │
│  │             │   │ Categoria    │   │ Algoritmo de Licença   │   │
│  └──────┬──────┘   │ Tipo=software│   │ Validade Padrão        │   │
│         │          │ Sem estoque  │   │                        │   │
│         │          └──────┬───────┘   └──────────┬─────────────┘   │
│         │                 │                      │                  │
│         ▼                 ▼                      ▼                  │
│  ┌──────────────────────────────────────────────────────────────┐   │
│  │                    PEDIDO (adaptado)                         │   │
│  │  num_pedido | id_cliente | id_produto | id_computador_cliente│   │
│  │  status_pagamento | valor | data | gateway_pagamento         │   │
│  └──────────────────────────┬───────────────────────────────────┘   │
│                             │                                       │
│              ┌──────────────┼──────────────┐                       │
│              ▼              ▼              ▼                       │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐            │
│  │  PAGAMENTO   │  │   LICENÇA    │  │ AGENDAMENTO  │            │
│  │  (existente) │  │   (NOVO)     │  │   (NOVO)     │            │
│  │              │  │              │  │              │            │
│  │ MercadoPago  │  │ Chave única  │  │ Data/hora    │            │
│  │ Pix          │  │ Hardware ID  │  │ AnyDesk ID   │            │
│  │ Cartão       │  │ Validade     │  │ Status       │            │
│  │              │  │ Status       │  │ Técnico resp │            │
│  │  WEBHOOK     │  │ Ativações    │  │ Instruções   │            │
│  │  ──────────► │  │              │  │              │            │
│  │  Gera licença│  └──────┬───────┘  └──────────────┘            │
│  └──────────────┘         │                                       │
│                           ▼                                       │
│  ┌────────────────────────────────────────────────────────────┐   │
│  │              API DE VALIDAÇÃO (endpoint REST)              │   │
│  │  POST /api/validate-license                                │   │
│  │  { hardware_id, license_key } → { valid: true/false }      │   │
│  └────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 5. Banco de Dados — Novas Tabelas Necessárias

### 5.1 `softwares`
```sql
CREATE TABLE softwares (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(255) NOT NULL,
    versao          VARCHAR(50)  NOT NULL,
    fabricante      VARCHAR(255),
    descricao       TEXT,
    imagem          VARCHAR(255),
    ativo           TINYINT(1) DEFAULT 1,
    algoritmo       ENUM('hmac_sha256', 'rsa', 'custom') DEFAULT 'hmac_sha256',
    validade_dias   INT DEFAULT 365,        -- 0 = vitalício
    max_ativacoes   INT DEFAULT 1,
    secret_key      VARCHAR(512) NOT NULL,  -- chave secreta do algoritmo (CRIPTOGRAFADA no banco)
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### 5.2 `licencas`
```sql
CREATE TABLE licencas (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido       INT NOT NULL,
    id_cliente      INT NOT NULL,
    id_software     INT NOT NULL,
    hardware_id     VARCHAR(512),           -- ID do computador (hash)
    chave_licenca   VARCHAR(512) NOT NULL UNIQUE,
    data_emissao    DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_expiracao  DATETIME,               -- NULL = vitalício
    status          ENUM('pendente','ativa','expirada','revogada') DEFAULT 'pendente',
    qtd_ativacoes   INT DEFAULT 0,
    max_ativacoes   INT DEFAULT 1,
    ip_ativacao     VARCHAR(45),
    ultimo_acesso   DATETIME,
    FOREIGN KEY (id_pedido)   REFERENCES pedidos(id),
    FOREIGN KEY (id_cliente)  REFERENCES clientes(id),
    FOREIGN KEY (id_software) REFERENCES softwares(id)
);
```

### 5.3 `ativacoes_licenca`
```sql
CREATE TABLE ativacoes_licenca (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_licenca      INT NOT NULL,
    hardware_id     VARCHAR(512) NOT NULL,
    data_ativacao   DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip              VARCHAR(45),
    user_agent      VARCHAR(512),
    status          ENUM('sucesso','falha','revogada') DEFAULT 'sucesso',
    FOREIGN KEY (id_licenca) REFERENCES licencas(id)
);
```

### 5.4 `agendamentos_instalacao`
```sql
CREATE TABLE agendamentos_instalacao (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    id_licenca      INT NOT NULL,
    id_cliente      INT NOT NULL,
    data_agendada   DATETIME NOT NULL,
    anydesk_id      VARCHAR(50),            -- ID AnyDesk fornecido pelo cliente
    senha_anydesk   VARCHAR(100),           -- Senha temporária (criptografada)
    status          ENUM('pendente','confirmado','realizado','cancelado') DEFAULT 'pendente',
    tecnico_resp    VARCHAR(255),
    observacoes     TEXT,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_licenca)  REFERENCES licencas(id),
    FOREIGN KEY (id_cliente)  REFERENCES clientes(id)
);
```

### 5.5 Alterações em tabelas existentes

```sql
-- Na tabela de produtos: indicar que é software (sem estoque físico, sem frete)
ALTER TABLE produtos ADD COLUMN tipo ENUM('fisico','digital','software') DEFAULT 'fisico';
ALTER TABLE produtos ADD COLUMN id_software INT NULL;

-- Na tabela de pedidos: capturar hardware ID durante checkout de software
ALTER TABLE pedidos ADD COLUMN hardware_id_cliente VARCHAR(512) NULL;
```

---

## 6. Funcionalidades a Implementar — Detalhamento Técnico

### 6.1 Sistema de Geração de Licenças

**Algoritmo sugerido: HMAC-SHA256**

```
chave_licenca = strtoupper(
    chunk_split(
        hash_hmac('sha256', hardware_id . pedido_id . software_id, SECRET_KEY),
        8, '-'
    )
)

Exemplo de saída:
AABBCCDD-EEFF0011-22334455-66778899-AABBCCDD-EEFF0011-22334455-66778899-
```

**Segurança:**
- `SECRET_KEY` armazenada criptografada no banco (AES-256)
- Hardware ID nunca armazenado em texto plano (SHA-256 do ID real)
- Rate limiting na API de validação (máx. 10 req/min por IP)
- Logs de todas as tentativas de ativação
- Possibilidade de revogar remotamente

**Fluxo de geração:**
```
1. Pagamento confirmado (webhook do gateway)
2. Sistema verifica status do pedido
3. Cria registro em `licencas` com status 'pendente'
4. Aguarda hardware_id do cliente (via formulário pós-compra ou email)
5. Quando hardware_id recebido: gera chave_licenca via HMAC-SHA256
6. Atualiza status para 'ativa'
7. Envia email com a chave + instruções
```

### 6.2 Captura do Hardware ID do Cliente

**Opção A — Script PowerShell (Windows) — RECOMENDADA:**
- Admin disponibiliza script `.ps1` para download na área do cliente
- Script coleta: UUID da placa-mãe, ID do processador, ID do disco
- Gera um hash combinado e exibe na tela
- Cliente copia e cola no site

```powershell
# Exemplo do script (disponibilizado no site)
$motherboard = (Get-WmiObject Win32_BaseBoard).SerialNumber
$cpu = (Get-WmiObject Win32_Processor).ProcessorId
$disk = (Get-WmiObject Win32_DiskDrive | Select-Object -First 1).SerialNumber
$combined = "$motherboard|$cpu|$disk"
$hash = [System.Security.Cryptography.HashAlgorithm]::Create('SHA256')
$bytes = [System.Text.Encoding]::UTF8.GetBytes($combined)
$hashBytes = $hash.ComputeHash($bytes)
$hardwareId = [System.BitConverter]::ToString($hashBytes) -replace '-',''
Write-Host "Seu Hardware ID: $hardwareId"
```

**Opção B — Automático via software cliente:**
- Software instalado coleta o ID automaticamente
- Envia para API e ativa a licença sem intervenção manual

### 6.3 API de Validação de Licença

Novo endpoint a criar: `src/Api/LicencaApiController.php`

```
POST /?m=api&c=licenca&a=validar
Body: { "hardware_id": "ABC123...", "license_key": "AABBCCDD-..." }

Resposta sucesso:
{
  "valid": true,
  "software": "SketchUp Pro 2024",
  "expiration": "2027-04-23",
  "activations_remaining": 0
}

Resposta falha:
{
  "valid": false,
  "reason": "hardware_mismatch" | "expired" | "revoked" | "not_found"
}
```

### 6.4 Checkout Adaptado para Software

Modificações em `CheckoutController`:
1. **Remover** lógica de CEP/frete para produtos tipo `software`
2. **Adicionar** campo "Hardware ID" no passo 2 do checkout
3. **Adicionar** link para instrução de coleta do Hardware ID
4. **Webhook** do MercadoPago → gera licença automaticamente após confirmação

### 6.5 Agendamento de Instalação Remota

Novo módulo `src/Agendamento/`:
- **Frontend:** calendário de disponibilidade (FullCalendar.js ou similar)
- **Backend:** CRUD de agendamentos + notificações por email
- **Fluxo:**
  1. Cliente acessa área `/minha-conta/licencas`
  2. Clica em "Agendar Instalação"
  3. Escolhe data/hora disponível
  4. Informa AnyDesk ID + senha temporária
  5. Recebe confirmação por email com instruções
  6. Admin recebe notificação no painel

---

## 7. Plano de Implementação por Etapas

### Etapa 1 — Preparação da Base (1–2 semanas)
- [ ] Fork/branch do projeto `ecommerce-erp`
- [ ] Criar banco de dados novo (não alterar o de produção)
- [ ] Executar migrations: novas tabelas `softwares`, `licencas`, `ativacoes_licenca`, `agendamentos_instalacao`
- [ ] Adicionar colunas nas tabelas `produtos` e `pedidos`
- [ ] Melhorar autenticação admin: substituir `md5()` por `password_hash()` / `password_verify()`
- [ ] Configurar variáveis de ambiente (`.env`) — retirar credenciais hardcoded do `Configuration.php`

### Etapa 2 — Módulo de Softwares e Produtos (1 semana)
- [ ] Criar `src/Software/` (DAO, Model, Controller admin)
- [ ] Adaptar `src/Sistema/Controller/ProdutoController.php` para suportar `tipo='software'`
- [ ] Criar interface admin para cadastrar softwares + vincular a produtos
- [ ] Cadastrar primeiro produto: SketchUp Pro 2024

### Etapa 3 — Sistema de Licenciamento (1–2 semanas)
- [ ] Criar `src/Licenca/Controller/LicencaController.php`
- [ ] Criar `src/Core/Dao/LicencaCoreDAO.php` e `LicencaCoreMODEL.php`
- [ ] Implementar `gerarChave($hardwareId, $pedidoId, $softwareId)` com HMAC-SHA256
- [ ] Criar `src/Api/LicencaApiController.php` (endpoint de validação)
- [ ] Implementar webhook handler para MercadoPago (gatilho de geração)
- [ ] Criar script PowerShell de coleta de Hardware ID
- [ ] Testes de geração e validação de chaves

### Etapa 4 — Checkout Adaptado (1 semana)
- [ ] Modificar `CheckoutController` para detectar produtos tipo `software`
- [ ] Remover etapa de frete/endereço para compras de software
- [ ] Adicionar campo de Hardware ID no checkout
- [ ] Testar fluxo completo: produto → carrinho → checkout → pagamento → licença gerada

### Etapa 5 — Área do Cliente / Frontend (1–2 semanas)
- [ ] Criar `src/Site/Controller/MinhaContaController.php`
- [ ] Tela de licenças adquiridas (exibir chave, status, expiração)
- [ ] Tela de reenvio de chave por email
- [ ] Redesign visual inspirado no KR Softs (dark, tech, profissional)
- [ ] Página de produto com: descrição, requisitos do sistema, screenshots, preço, CTA

### Etapa 6 — Agendamento de Instalação (1 semana)
- [ ] Criar `src/Agendamento/` (Controller, DAO, Model)
- [ ] View frontend: calendário de agendamento com FullCalendar.js
- [ ] Formulário de AnyDesk ID + senha
- [ ] Emails automáticos de confirmação/lembrete
- [ ] Painel admin de agendamentos

### Etapa 7 — Painel Admin — Módulo de Licenças (1 semana)
- [ ] Tela de listagem de licenças (filtros: status, software, cliente, data)
- [ ] Ação de revogar licença
- [ ] Ação de reenviar email com chave
- [ ] Ação de estender validade
- [ ] Visualização de log de ativações
- [ ] Tela de agendamentos pendentes/realizados

### Etapa 8 — Testes e Segurança (1 semana)
- [ ] Testes de penetração básicos (SQLi, XSS, IDOR nas licenças)
- [ ] Rate limiting na API de validação
- [ ] Auditoria de credenciais hardcoded
- [ ] Testes de fluxo completo (compra → licença → ativação)
- [ ] Configurar HTTPS obrigatório

---

## 8. Melhorias Necessárias no ERP Atual

### 🔴 Críticas (segurança)

| Problema | Localização | Solução |
|---|---|---|
| Senhas com MD5 | `LoginController.php` linha ~47 | Substituir por `password_hash()` + `password_verify()` |
| Credenciais hardcoded | `Configuration.php` | Migrar para variáveis de ambiente via `.env` |
| Tokens de produção no código | `PagamentoMPController.php` linhas 23–26 | Mover para `.env` |
| Chaves Pagarme no código | `PagamentoPagarmeController.php` | Mover para `.env` |
| SQL injection risk em filtros admin | `PainelController.php` | Usar bind params do Doctrine DBAL |
| Credenciais SkyHub hardcoded | `index.php` linhas ~95-96 | Mover para `.env` |
| `display_errors = 1` em produção | `index.php` linha ~42 | Condicionar ao ambiente |

### 🟡 Importantes (qualidade)

| Problema | Solução |
|---|---|
| PHPMailer versão ~5.2 (antiga) | Atualizar para 6.x |
| `php >= 7.4` | Atualizar para PHP 8.2+ (melhor performance e segurança) |
| Ausência de `.env` | Adicionar `vlucas/phpdotenv` ao composer |
| Sem validação CSRF em formulários | Implementar token CSRF |
| Sessão sem regeneração de ID | Adicionar `session_regenerate_id()` no login |

### 🟢 Melhorias (performance e UX)

| Melhoria | Benefício |
|---|---|
| Cache de configurações (já existe hook para Memcached) | Reduzir queries por request |
| Paginação já implementada (`PaginationUtil`) | Reutilizar em todas as listagens |
| Adicionar CDN para assets estáticos | Melhor performance no frontend |

---

## 9. Sugestões de Tecnologias Adicionais

| Finalidade | Sugestão | Justificativa |
|---|---|---|
| Calendário de agendamento | `FullCalendar.js` (v6) | Gratuito, bem mantido, fácil integração |
| Variáveis de ambiente | `vlucas/phpdotenv` | Padrão da indústria para PHP |
| Rate limiting API | Middleware de contagem por IP em Redis/Memcached | Previne brute force nas chaves |
| QR Code de licença | `endroid/qr-code` via Composer | Para exibir chave em formato QR |
| Criptografia das chaves secretas | `defuse/php-encryption` | Criptografia simétrica segura em PHP |
| Monitoramento de erros | Sentry (plano gratuito) | Alertas de erros em produção |
| Deploy automático | GitHub Actions + `deploy-prod.sh` existente | CI/CD básico |

---

## 10. Riscos e Desafios

### 🔴 Risco Alto

| Risco | Probabilidade | Impacto | Mitigação |
|---|---|---|---|
| **Pirataria das chaves geradas** | Alta | Alto | Vincular chave ao hardware ID; API de validação online; expiração obrigatória |
| **Compartilhamento de Hardware ID falso** | Média | Alto | Validar consistência do hardware ID (múltiplos campos); limite de tentativas |
| **Bypass da API de validação** | Média | Alto | Obuscar lógica no cliente; checar online periodicamente; sem modo offline permanente |
| **Chave secreta vazada** | Baixa | Crítico | Rotacionar chaves regularmente; armazenar criptografada; monitorar repositório |

### 🟡 Risco Médio

| Risco | Mitigação |
|---|---|
| **Webhook de pagamento não recebido** | Implementar polling de status + retry automático |
| **Cliente muda hardware e perde licença** | Permitir N reativações no admin (com limite configurável) |
| **Agendamento em horário já ocupado** | Controle de disponibilidade com bloqueio em banco de dados |
| **Dados de AnyDesk interceptados** | Nunca armazenar senha real; usar SSL; apagar após sessão |

### 🟢 Risco Baixo

| Risco | Mitigação |
|---|---|
| **Email de licença cai no spam** | SPF/DKIM/DMARC configurados + domínio de envio dedicado |
| **Escalabilidade do banco** | Índices nas colunas `hardware_id`, `chave_licenca`; MySQL bem indexado suporta milhões de licenças |

---

## 11. Estrutura de Pastas Proposta para o Novo Módulo

```
src/
├── Api/
│   └── Controller/
│       └── LicencaApiController.php       ← Endpoint REST de validação
├── Agendamento/
│   ├── Controller/
│   │   └── AgendamentoController.php
│   ├── Dao/
│   │   └── AgendamentoDao.php
│   └── View/
│       ├── calendário.php
│       └── confirmacao.php
├── Licenca/
│   ├── Controller/
│   │   └── LicencaController.php
│   ├── Dao/
│   │   └── LicencaDao.php
│   ├── Service/
│   │   └── LicencaService.php             ← Lógica de geração de chave
│   └── View/
│       └── minhas-licencas.php
└── Software/
    ├── Controller/
    │   └── SoftwareController.php         ← CRUD admin de softwares
    ├── Dao/
    │   └── SoftwareDao.php
    └── View/
        └── index.php

public/
└── scripts/
    └── coletar-hardware-id.ps1            ← Script PowerShell para clientes Windows
```

---

## 12. Estimativa de Esforço

| Etapa | Esforço Estimado | Complexidade |
|---|---|---|
| 1 — Preparação da base | 3–5 dias | Baixa |
| 2 — Módulo de Softwares | 3–4 dias | Baixa |
| 3 — Sistema de Licenciamento | 5–8 dias | **Alta** |
| 4 — Checkout Adaptado | 3–4 dias | Média |
| 5 — Área do Cliente / Frontend | 5–8 dias | Média |
| 6 — Agendamento de Instalação | 3–5 dias | Média |
| 7 — Painel Admin (licenças) | 3–4 dias | Baixa |
| 8 — Testes e Segurança | 3–5 dias | Média |
| **Total** | **~30–43 dias** | — |

---

## 13. Ordem de Implementação Recomendada (MVP)

Para um **MVP funcional o mais rápido possível**, priorizar:

```
1. Segurança básica (credenciais em .env, MD5 → password_hash)
2. Cadastro de produto tipo=software (sem frete)
3. Checkout → Pagamento → Webhook → Gera licença (fluxo principal)
4. Email com a chave gerada para o cliente
5. Área do cliente básica (ver licenças)
```

Isso já entrega o ciclo completo de venda de licença. As funcionalidades de agendamento e painel avançado podem vir depois.

---

*Documento gerado em Abril 2026. Revisar à medida que o desenvolvimento avança.*
