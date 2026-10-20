#!/usr/bin/env bash
# Builds dist/webni-custom-version-1.zip from the committed tree (dev files are export-ignored via .gitattributes).
set -euo pipefail
cd "$(dirname "$0")/.."
version=$(sed -n 's/^Version: *//p' style.css | head -1)
mkdir -p dist
rm -f dist/webni-custom-version-1.zip
git archive --format=zip --prefix=webni-custom-version-1/ -o dist/webni-custom-version-1.zip HEAD
echo "dist/webni-custom-version-1.zip (theme version ${version}, $(git rev-parse --short HEAD))"
