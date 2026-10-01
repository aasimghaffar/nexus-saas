#!/usr/bin/env bash
# Assemble the CodeCanyon release zip for Nexus SaaS (Laravel).
# Usage: bash build-release.sh 1.0.0
set -euo pipefail

VERSION="${1:-1.0.0}"
OUT="dist/nexus-saas-laravel-v${VERSION}"

echo "→ Building release v${VERSION}"
rm -rf dist && mkdir -p "${OUT}/Main File" "${OUT}/Documentation"

# 1) Production assets: rebuild when possible, otherwise use the shipped build
if [ -d node_modules ]; then
  npm run build
elif [ -f public/build/manifest.json ]; then
  echo "→ Using prebuilt public/build (run 'npm install' first if you changed CSS/JS)."
else
  echo "!! No compiled assets. Run 'npm install && npm run build' first."
  exit 1
fi

# 2) Copy application code
rsync -a ./ "${OUT}/Main File/nexus-saas/" \
  --exclude .git --exclude node_modules --exclude vendor \
  --exclude .env --exclude storage/installed.json \
  --exclude dist --exclude tests --exclude .phpunit.cache \
  --exclude storage/app/private --exclude storage/logs \
  --exclude PACKAGING.md --exclude codecanyon-submission.md --exclude INSTALL-LOCAL.md --exclude build-release.sh

# 3) Keep runtime directories present but empty
for d in storage/logs storage/app/private storage/framework/{cache,sessions,views}; do
  mkdir -p "${OUT}/Main File/nexus-saas/${d}"
  touch "${OUT}/Main File/nexus-saas/${d}/.gitkeep"
done

# 4) Documentation + top-level readme
cp DOCUMENTATION.md "${OUT}/Documentation/"
cp CHANGELOG.md "${OUT}/Documentation/" 2>/dev/null || true
cat > "${OUT}/README.txt" <<TXT
Nexus SaaS - Laravel Admin & Starter Kit v${VERSION}
====================================================
1. Upload 'Main File/nexus-saas' to your server.
2. Point your web root at nexus-saas/public.
3. Run: composer install --no-dev
4. Visit your site URL and follow the one-page installer.
Full guide: Documentation/DOCUMENTATION.md
TXT

# 5) Zip it
( cd dist && zip -qr "nexus-saas-laravel-v${VERSION}.zip" "nexus-saas-laravel-v${VERSION}" )
echo "✓ dist/nexus-saas-laravel-v${VERSION}.zip"
