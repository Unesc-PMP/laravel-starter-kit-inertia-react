#!/usr/bin/env bash
set -euo pipefail

input=$(cat)
file=$(echo "$input" | jq -r '.file_path // empty')
root=$(echo "$input" | jq -r '.workspace_roots[0] // empty')

if [[ -n "$file" && "$file" != /* && -n "$root" ]]; then
  file="$root/$file"
fi

[[ -n "$file" ]] || exit 0
[[ "$file" == *.php ]] || exit 0
[[ "$file" == */vendor/* || "$file" == */node_modules/* ]] && exit 0

if [[ -n "$root" && -f "$root/composer.json" ]]; then
  cd "$root"
elif [[ -f composer.json ]]; then
  :
else
  exit 0
fi

if ! vendor/bin/sail ps 2>/dev/null | grep -q "Up"; then
  echo "Sail offline — Pint/testes não executados (sail up -d)." >&2
  exit 0
fi

container_path="${file#"$PWD"/}"
container_path="${container_path#/}"

vendor/bin/sail bin pint "$container_path" --format agent >&2 || true

if [[ "$file" == *"/tests/"*.php ]]; then
  test_name=$(basename "$file" .php)
  echo "→ Rodando $test_name" >&2
  if ! vendor/bin/sail artisan test --compact --filter="$test_name" >&2; then
    echo "↑ Teste falhou — corrija antes de prosseguir." >&2
    exit 2
  fi
fi

exit 0
