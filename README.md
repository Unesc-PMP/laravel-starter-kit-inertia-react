- Versão Inertia & React (este projeto): **[github.com/nunomaduro/laravel-starter-kit-inertia-react](https://github.com/nunomaduro/laravel-starter-kit-inertia-react)**
- Versão Blade: **[github.com/nunomaduro/laravel-starter-kit](https://github.com/nunomaduro/laravel-starter-kit)**
- Versão Inertia & Vue: **[github.com/nunomaduro/laravel-starter-kit-inertia-vue](https://github.com/nunomaduro/laravel-starter-kit-inertia-vue)**

<p align="center">
    <a href="https://youtu.be/VhzP0XWGTC4" target="_blank">
        <img src="https://github.com/nunomaduro/laravel-starter-kit/blob/main/art/banner.png" alt="Visão geral do Laravel Starter Kit" style="width:70%;">
    </a>
</p>

<p>
    <a href="https://github.com/nunomaduro/laravel-starter-kit-inertia-react/actions"><img src="https://github.com/nunomaduro/laravel-starter-kit-inertia-react/actions/workflows/tests.yml/badge.svg" alt="Status do build"></a>
    <a href="https://packagist.org/packages/nunomaduro/laravel-starter-kit-inertia-react"><img src="https://img.shields.io/packagist/dt/nunomaduro/laravel-starter-kit-inertia-react" alt="Total de downloads"></a>
    <a href="https://packagist.org/packages/nunomaduro/laravel-starter-kit-inertia-react"><img src="https://img.shields.io/packagist/v/nunomaduro/laravel-starter-kit-inertia-react" alt="Última versão estável"></a>
    <a href="https://packagist.org/packages/nunomaduro/laravel-starter-kit-inertia-react"><img src="https://img.shields.io/packagist/l/nunomaduro/laravel-starter-kit-inertia-react" alt="Licença"></a>
    <a href="https://youtube.com/@nunomaduro?sub_confirmation=1"><img alt="Inscritos no canal do YouTube" src="https://img.shields.io/youtube/channel/subscribers/UCO_hYZF2gb_CyG5sA7ArlGg?style=flat&label=youtube&color=brightgreen"></a>
</p>

**Laravel Starter Kit (Inertia & React)** é um esqueleto [Laravel](https://laravel.com) ultra-rigoroso e type-safe, pensado para quem não abre mão de qualidade de código. Este starter kit opinativo impõe padrões de desenvolvimento exigentes por meio de ferramentas bem configuradas e decisões de arquitetura que priorizam segurança de tipos, imutabilidade e falha rápida.

## Por que este starter kit?

O PHP moderno evoluiu para uma linguagem madura e type-safe, mas muitos projetos Laravel ainda usam convenções soltas e tipagem opcional. Este starter kit muda esse paradigma ao impor:

- **Arquitetura orientada a Actions**: cada operação fica em uma classe de ação única
- **Cruddy by Design**: operações CRUD padronizadas em controllers, actions e páginas Inertia & React
- **100% de cobertura de tipos**: todo método, propriedade e parâmetro é tipado explicitamente
- **Tolerância zero a code smells**: Rector, PHPStan, OxLint e Oxfmt no máximo rigor pegam problemas antes de virarem bugs
- **Arquitetura imutável em primeiro lugar**: estruturas de dados favorecem imutabilidade para evitar mutações inesperadas
- **Filosofia fail-fast**: erros são detectados em tempo de compilação, não em runtime
- **Qualidade de código automatizada**: ferramentas pré-configuradas mantêm o código consistente em todo o time
- **Defaults Laravel melhores**: graças ao **[Essentials](https://github.com/nunomaduro/essentials)** — models estritos, eager loading automático, datas imutáveis e mais
- **Diretrizes de IA**: guidelines integradas para ajudar a manter qualidade e consistência
- **Suíte de testes completa**: mais de 150 testes com 100% de cobertura de código usando Pest
- **[Laravel PAO](https://github.com/laravel/pao)**: saída JSON compacta para Agents em Pest, PHPStan, Rector e Artisan — sem alterar a experiência no terminal humano

Não é só mais um boilerplate Laravel — é a ideia de que aplicações PHP podem e devem ser construídas com o mesmo rigor de linguagens fortemente tipadas como Rust ou TypeScript.

## Começando

O ambiente local usa **[Laravel Sail](https://laravel.com/docs/sail)** (Docker): PHP 8.5, PostgreSQL, Redis e Mailpit. Você **não precisa** instalar PHP, Composer ou Bun no host — só **Git** e **Docker**.

No WSL2 (Ubuntu): instale o [Docker Desktop](https://www.docker.com/products/docker-desktop/) no Windows, ative **WSL Integration** para sua distro e confira `docker compose version`.

### Primeira vez

```bash
git clone <url-do-repositorio>
cd laravel-starter-kit-inertia-react

# Dependências PHP (sem Composer no host)
docker run --rm \
  -v "$(pwd):/app" -w /app \
  composer:latest \
  composer install --ignore-platform-req=ext-sockets

cp .env.example .env
echo "WWWGROUP=$(id -g)" >> .env
echo "WWWUSER=$(id -u)" >> .env

./vendor/bin/sail build
./vendor/bin/sail up -d

./vendor/bin/sail composer setup
./vendor/bin/sail bunx playwright install
```

O script `composer setup` gera a `APP_KEY`, roda as migrations, instala dependências Bun e faz o build do frontend.

### Desenvolvimento

```bash
./vendor/bin/sail up -d              # app em http://localhost
./vendor/bin/sail bun run dev        # Vite (http://localhost:5173)
./vendor/bin/sail stop               # parar containers
```

| Serviço | URL |
|--------|-----|
| Aplicação | http://localhost |
| Vite | http://localhost:5173 |
| Mailpit | http://localhost:8025 |

Alias opcional (`~/.bashrc`):

```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

Depois use `sail` no lugar de `./vendor/bin/sail`.

### Validar instalação

```bash
./vendor/bin/sail composer test
```

Deve passar com 100% de cobertura e todas as checagens de qualidade.

> **Stack local (sem Docker):** se você já tiver PHP 8.5+, Composer, Bun e Xdebug no host, pode usar `composer setup`, `composer dev` e `composer test` diretamente. Os testes usam SQLite em memória (`phpunit.xml`); o `.env` de desenvolvimento aponta para PostgreSQL via Sail.

## Ferramentas disponíveis

Execute os scripts **dentro do Sail** com `sail composer …` ou `sail bun …` (ou `./vendor/bin/sail`).

### Desenvolvimento

- `sail bun run dev` — Vite com HMR (use com `sail up -d`)
- `sail artisan queue:listen` — worker de filas, se precisar
- `sail artisan pail` — logs em tempo real
- `sail composer dev` — sobe servidor, filas, **Pail** e Vite juntos

### Observabilidade e debug (local)

Pacotes para inspecionar requests, queries, logs e exceções em desenvolvimento. Funcionam com `APP_DEBUG=true` e **não** devem ir para produção.

| Ferramenta | Pacote | Acesso rápido |
|------------|--------|---------------|
| Log Viewer | `opcodesio/log-viewer` | http://localhost/log-viewer |
| Debugbar | `fruitcake/laravel-debugbar` | barra no rodapé de cada página |
| Agent Debugger | `clcbws/laravel-agents-debug` | http://localhost/_agent_debug/dashboard |
| LaraDumps | `laradumps/laradumps` | app desktop (porta 9191) |
| Pail | `laravel/pail` | terminal (`sail artisan pail`) |

#### Log Viewer

Interface web para ler `storage/logs/laravel.log` (busca, filtros, download). Com o Sail rodando, acesse **http://localhost/log-viewer**.

Em produção, configure autorização em `AppServiceProvider` (`LogViewer::auth(...)`) antes de expor a rota.

#### Laravel Debugbar

Ativo automaticamente quando `APP_DEBUG=true`. Mostra queries, tempo, memória, rotas, sessão e exceções na barra inferior do navegador.

```bash
# CLI — útil para Agents e terminal
sail artisan debugbar:find --issues --max=20   # requests com N+1, queries lentas, etc.
sail artisan debugbar:get latest               # detalhe da última request
sail artisan debugbar:queries {id}             # analisar SQL de uma request
sail artisan debugbar:clear                    # limpar histórico em storage/debugbar/
```

Os snapshots ficam em `storage/debugbar/` (gitignored). Config em `config/debugbar.php`.

#### Laravel Agent Debugger

Dashboard server-side com profilers (N+1, transações, gates, SQL playground, mocks, testes). Pensado para humanos e Agents de IA.

```bash
sail artisan agent:debug-on       # ativar (primeira vez)
sail artisan agent:debug-status   # ver se está ativo
sail artisan agent:debug-tail     # stream de logs no terminal
sail artisan agent:debug-off      # desativar
```

Depois abra http://localhost/_agent_debug/dashboard.

#### LaraDumps

Envia dumps para o [app desktop LaraDumps](https://laradumps.dev/get-started/installation.html) — fora do browser, sem poluir a resposta HTML.

1. Instale e abra o app desktop no host (Windows/macOS/Linux).
2. No código:

```php
ds('valor');           // dump
ds()->label('user')->dump($user);
```

3. Com Sail/WSL, o pacote usa `host.docker.internal:9191` para falar com o app no host. Gere ou ajuste `laradumps.yaml` com `sail artisan ds:init` (arquivo gitignored).

Observers (queries, mail, jobs, etc.) ligam/desligam em `laradumps.yaml` → seção `observers`.

#### Pail

Tail dos logs Laravel no terminal, com cores e filtros.

```bash
sail artisan pail
sail artisan pail --filter="error"
```

Já incluso no script `composer dev` (roda em paralelo com servidor e Vite).

### Qualidade de código

- `sail composer lint` — Rector, Pint e Oxfmt
- `sail composer test:lint` — modo dry-run (CI)

### Testes

- `sail composer test:type-coverage` — cobertura de tipos (Pest), mínimo 100%
- `sail composer test:types` — PHPStan (nível 9) e checagem TypeScript
- `sail composer test:unit` — Pest com cobertura de código 100%
- `sail composer test` — suíte completa (tipos, testes, lint, análise estática); com Agent ativo, a saída de cada etapa passa pelo PAO (ver abaixo)

### Manutenção

- `sail composer update:requirements` — atualiza dependências PHP e Bun

### Laravel PAO (saída otimizada para Agent)

O projeto inclui **[Laravel PAO](https://github.com/laravel/pao)** (`laravel/pao` em `require-dev`). O PAO (*PHP Agent Output*) detecta quando Pest, PHPUnit, Paratest, PHPStan ou Rector rodam em um ambiente de **Agent de IA** (Cursor, Claude Code, GitHub Copilot, Gemini CLI, Devin, etc.) e substitui a saída verbosa por **JSON compacto e estável em tamanho**. No terminal humano — ou no CI, onde não há variáveis de Agent — a saída colorida e formatada **não muda**.

**Zero configuração:** após `sail composer install`, o pacote é carregado automaticamente (autoload do Composer, plugin Pest e service provider Laravel para comandos `artisan`). Não há `config/pao.php` para publicar.

#### Ferramentas e scripts afetados

| Ferramenta | Ativado via | Scripts do projeto |
|------------|-------------|-------------------|
| Pest | `pest`, `vendor/bin/pest` | `test:type-coverage`, `test:unit`, `composer test` |
| PHPStan | `phpstan`, `vendor/bin/phpstan` | `test:types` |
| Rector | `rector`, `vendor/bin/rector` | `lint`, `test:lint` |
| Laravel Pint | `pint` (saída JSON quando Agent) | `lint`, `test:lint` |
| Artisan | qualquer comando no console | ex.: `sail artisan about`, `migrate:status` (menos ruído visual) |

#### Detecção de Agent

O PAO usa [laravel/agent-detector](https://github.com/laravel/agent-detector). Exemplos de sinais reconhecidos:

- **Cursor:** `CURSOR_AGENT`
- **Claude Code:** `CLAUDE_CODE`, `CLAUDECODE`
- **Copilot:** `COPILOT_MODEL`, `COPILOT_CLI`, …
- **Genérico:** `AI_AGENT`

Lista completa no repositório do pacote.

#### Exemplos de saída

**Testes passando** (Agent ativo):

```json
{
  "tool": "pest",
  "result": "passed",
  "tests": 164,
  "passed": 164,
  "assertions": 485,
  "duration_ms": 3038
}
```

**Testes falhando** — inclui `failures` com arquivo, linha e mensagem:

```json
{
  "tool": "pest",
  "result": "failed",
  "tests": 10,
  "passed": 9,
  "failed": 1,
  "failures": [
    {
      "test": "it validates email",
      "file": "/var/www/html/tests/Unit/Rules/ValidEmailTest.php",
      "line": 12,
      "message": "Failed asserting that false is true."
    }
  ],
  "duration_ms": 420
}
```

**PHPStan:**

```json
{
  "tool": "phpstan",
  "result": "passed",
  "errors": 0
}
```

Com `--coverage` ou `--profile`, linhas extras podem aparecer no array `raw` (ainda em JSON).

#### Uso no dia a dia

```bash
# Suíte completa (cada etapa em JSON se o Agent estiver ativo)
vendor/bin/sail composer test

# Um arquivo de teste
vendor/bin/sail artisan test --compact tests/Unit/Rules/ValidEmailTest.php

# Forçar saída humana/verbosa mesmo dentro do Cursor
PAO_DISABLE=1 vendor/bin/sail composer test
```

#### Integração com hooks do Cursor

O hook `pint-and-test.sh` executa `sail artisan test` após editar arquivos em `tests/`. Com o Cursor Agent ativo, o PAO reduz o ruído no canal **Hooks** e facilita o Agent interpretar falhas.

#### CI vs desenvolvimento local

O workflow [`.github/workflows/tests.yml`](.github/workflows/tests.yml) roda `composer test` **sem** variáveis de Agent — os logs do GitHub Actions mantêm a saída tradicional das ferramentas. Localmente, abrir o terminal integrado do Cursor costuma ativar o PAO automaticamente.

### Hooks do Cursor (Agent)

O projeto inclui [hooks do Cursor](https://cursor.com/docs/agent/hooks) em `.cursor/hooks.json`, inspirados no harness do [worthly-api](https://github.com/beerandcodeteam/worthly-api/tree/main/.claude/hooks). Eles automatizam qualidade de código enquanto o Agent edita arquivos e executa comandos no terminal.

| Evento | Script | O que faz |
|--------|--------|-----------|
| `beforeShellExecution` | `block-non-sail.sh` | Bloqueia `php`, `composer`, `bun`, `npm`, `pest`, `artisan` etc. **fora** do Sail |
| `afterFileEdit` | `pint-and-test.sh` | Roda Pint no `.php` editado; se for `tests/`, executa Pest com `--filter` (saída JSON via [PAO](https://github.com/laravel/pao) quando o Agent está ativo) |
| `beforeSubmitPrompt` / `postToolUse` / `stop` | `log-event.sh` | Grava eventos em `.harness/events.jsonl` (gitignored) |
| `stop` | `notify-n8n.sh` | Webhook opcional; com Ralph ativo, inclui fase/tentativa via `RALPH_*` |

**Pré-requisitos no host:** [jq](https://jqlang.org/) instalado e Sail em execução (`sail up -d`) para Pint e testes automáticos. Se o Sail estiver parado, `pint-and-test.sh` apenas avisa e não bloqueia.

**Comandos corretos para o Agent** (o hook rejeita a forma direta no host):

```bash
# ❌ bloqueado pelo hook
php artisan test
composer test

# ✅ permitido
vendor/bin/sail artisan test --compact
vendor/bin/sail composer test
```

**Notificação n8n (opcional):** copie `.cursor/.env.example` para `.cursor/.env` (gitignored) e defina:

```env
HARNESS_NOTIFY_WEBHOOK_URL=https://seu-n8n.example/webhook/...
HARNESS_NOTIFY_PASSWORD=
```

**Depuração:** aba **Hooks** nas configurações do Cursor ou canal de saída **Hooks** — útil para ver bloqueios, Pint e falhas de teste. Reinicie o Cursor após alterar `hooks.json`.

A suíte completa (cobertura 100%, lint, types, browser) continua sendo `sail composer test` (local) ou o workflow de CI — os hooks cobrem feedback rápido no dia a dia, não substituem o pipeline.

### Ralph (orquestrador de fases no Cursor)

O script [`ralph.sh`](ralph.sh) adapta o loop de fases do [worthly-api](https://github.com/beerandcodeteam/worthly-api/blob/main/ralph.sh) para este starter kit: lê um plano em Markdown, executa cada fase com o **Cursor Agent CLI** e valida com Sail entre fases.

**Pré-requisitos**

1. [Cursor Agent CLI](https://cursor.com/docs/cli) instalado (`agent`) e autenticado: `agent login`
2. Plano de fases: copie o exemplo (estrutura alinhada ao [worthly-api `project-phases.md`](https://github.com/beerandcodeteam/worthly-api/blob/main/docs/project-phases.md) — sub-fases `###`, tarefas numeradas, blocos **Feature tests**) e edite:

```bash
cp docs/project-phases.example.md docs/project-phases.md
```

3. Sail em execução: `vendor/bin/sail up -d`
4. `jq` no host (usado pelos hooks)

**Uso**

```bash
chmod +x ralph.sh
./ralph.sh                          # docs/project-phases.md, engine cursor
./ralph.sh --engine cursor docs/project-phases.md
RALPH_VALIDATE=full ./ralph.sh      # validação: sail composer test
RALPH_VALIDATE=none ./ralph.sh      # sem validação automática entre fases
./ralph.sh --status                 # ver fases ✓/○ e barra de progresso (sem executar)
./ralph.sh --quiet                  # spinner + logs (padrão)
./ralph.sh --verbose                # repete saída do agent no console
./ralph.sh --yes                    # sem confirmações interativas
```

**Saída no console:** o Ralph imprime banner, lista de fases, barra `[████░░]` por fase, passos (`→ Agent`, `→ Validação`), relatório final e caminho dos logs em `.phases/logs/`. No modo `--quiet`, um spinner indica que o agent está rodando; use `tail -f .phases/logs/phase-01-….log` para acompanhar em tempo real.

| Variável / flag | Valores | Padrão | Descrição |
|----------|---------|--------|-----------|
| `RALPH_VALIDATE` | `quick`, `full`, `none` | `quick` | `quick` = `sail artisan test --compact`; `full` = `sail composer test` |
| `RALPH_VERBOSE` | `0`, `1` | `0` | `0` = `--quiet` (spinner); `1` = stream do agent no terminal |
| `--engine` | `cursor`, `claude`, `codex` | `cursor` | `cursor` usa `agent -p --force --trust` |

Cada **execução** do Ralph corresponde a um cabeçalho `## Phase N — Título` (sub-fases `### Phase N.M` ficam no mesmo prompt, como no worthly). O script grava prompts, logs e progresso em `.phases/` (gitignored) e pode fazer commit por fase se o repositório estiver limpo.

**Integração com hooks**

- `block-non-sail.sh` permite `./ralph.sh` e `agent` no host (o Agent CLI não roda dentro do container).
- Durante o Ralph, `ralph.sh` exporta `RALPH_PHASE_*` para o ambiente; o hook `notify-n8n.sh` (no evento `stop`) monta mensagens com contexto da fase quando `HARNESS_NOTIFY_WEBHOOK_URL` estiver definido.

**Engines**

| Engine | Comando | Quando usar |
|--------|---------|-------------|
| `cursor` | `agent -p --force --trust --workspace …` | Padrão — mesmo ecossistema dos hooks em `.cursor/` |
| `claude` / `codex` | CLIs legadas | Só se você já usa o harness worthly no host |

## Licença

**Laravel Starter Kit Inertia React** foi criado por **[Nuno Maduro](https://x.com/enunomaduro)** sob a **[licença MIT](https://opensource.org/licenses/MIT)**.
