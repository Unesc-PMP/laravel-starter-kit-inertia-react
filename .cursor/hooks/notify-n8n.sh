#!/usr/bin/env bash
set -euo pipefail

[[ -f .cursor/.env ]] && set -a && source .cursor/.env && set +a

input=$(cat)
root=$(echo "$input" | jq -r '.workspace_roots[0] // empty')

if [[ -n "$root" && -d "$root" ]]; then
  cd "$root"
fi

webhook_url="${HARNESS_NOTIFY_WEBHOOK_URL:-}"
[[ -z "$webhook_url" ]] && exit 0

event=$(echo "$input" | jq -r '.hook_event_name // "Unknown"')
msg=$(echo "$input" | jq -r '.message // empty')
session=$(echo "$input" | jq -r '.conversation_id // .session_id // empty')
branch=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "?")

if [[ -z "$msg" ]]; then
  msg="[cursor] ${event} em ${branch}"
fi

payload=$(jq -n \
  --arg event "$event" \
  --arg msg "$msg" \
  --arg session "$session" \
  --arg branch "$branch" \
  --arg cwd "$PWD" \
  --arg ts "$(date -u +%Y-%m-%dT%H:%M:%SZ)" \
  '{event: $event, message: $msg, session_id: $session, branch: $branch, cwd: $cwd, ts: $ts}')

curl_args=(-sS -X POST -H "Content-Type: application/json" -d "$payload")

if [[ -n "${HARNESS_NOTIFY_PASSWORD:-}" ]]; then
  curl_args+=(-u "beerandcode:${HARNESS_NOTIFY_PASSWORD}")
fi

curl "${curl_args[@]}" "$webhook_url" >/dev/null 2>&1 || true

exit 0
