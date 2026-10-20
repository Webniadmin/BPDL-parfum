#!/usr/bin/env bash
# Syntax-checks every PHP file in the theme.
set -euo pipefail
cd "$(dirname "$0")/.."
status=0
while IFS= read -r -d '' file; do
  if ! php -l "$file" > /dev/null 2>&1; then
    php -l "$file" || true
    status=1
  fi
done < <(find . -name '*.php' -not -path './node_modules/*' -print0)
if [ "$status" -eq 0 ]; then echo "PHP syntax OK"; fi
exit $status
