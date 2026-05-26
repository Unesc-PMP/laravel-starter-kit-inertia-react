#!/usr/bin/env bash
set -euo pipefail

input=$(cat)
cmd=$(echo "$input" | jq -r '.command // empty')
[[ -z "$cmd" ]] && exit 0

# já usa sail → libera
echo "$cmd" | grep -qE '(^|[[:space:];&|])(\./)?vendor/bin/sail([[:space:]]|$)|(^|[[:space:];&|])sail([[:space:]]|$)' && exit 0

# libera git no host (evita falso positivo com "composer"/"artisan" em mensagens de commit)
if echo "$cmd" | grep -qE '(^|[[:space:];&|])git[[:space:]]+(add|commit|status|diff|log|push|pull|fetch|merge|rebase|checkout|branch|stash|restore|reset|show|rev-parse)'; then
  exit 0
fi

# bloqueia php/composer/bun/npm/pest/phpunit/artisan diretos no host
if echo "$cmd" | grep -qE '(^|[[:space:];&|])(php|composer|bun|npm|pest|phpunit|artisan)([[:space:]]|$)'; then
  cat >&2 <<EOF
Bloqueado: este projeto roda dentro do Sail.

  Tentativa: $cmd

  Use vendor/bin/sail. Exemplos:
    vendor/bin/sail artisan ...
    vendor/bin/sail composer ...
    vendor/bin/sail bun run dev
    vendor/bin/sail artisan test
EOF
  exit 2
fi

exit 0
