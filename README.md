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

### Qualidade de código

- `sail composer lint` — Rector, Pint e Oxfmt
- `sail composer test:lint` — modo dry-run (CI)

### Testes

- `sail composer test:type-coverage` — cobertura de tipos (Pest), mínimo 100%
- `sail composer test:types` — PHPStan (nível 9) e checagem TypeScript
- `sail composer test:unit` — Pest com cobertura de código 100%
- `sail composer test` — suíte completa (tipos, testes, lint, análise estática)

### Manutenção

- `sail composer update:requirements` — atualiza dependências PHP e Bun

## Licença

**Laravel Starter Kit Inertia React** foi criado por **[Nuno Maduro](https://x.com/enunomaduro)** sob a **[licença MIT](https://opensource.org/licenses/MIT)**.
