#!/usr/bin/env bash
set -euo pipefail

SSH_HOST="${SSH_HOST:-loterica}"
APP_USER="${APP_USER:-evamodamodesta}"
APP_DIR="${APP_DIR:-/home/evamodamodesta/public_html}"
BRANCH="${BRANCH:-main}"
REMOTE_URL="${REMOTE_URL:-https://github.com/GuilhermeDeJesus/ecommerce-erp.git}"

echo "[deploy] Host: ${SSH_HOST}"
echo "[deploy] App dir: ${APP_DIR}"
echo "[deploy] Branch: ${BRANCH}"

ssh "${SSH_HOST}" "sudo -u ${APP_USER} -H bash -lc '
set -euo pipefail
cd "${APP_DIR}"

if [ ! -d .git ]; then
  git init -b "${BRANCH}"
fi

if git remote get-url origin >/dev/null 2>&1; then
  git remote set-url origin "${REMOTE_URL}"
else
  git remote add origin "${REMOTE_URL}"
fi

git fetch origin "${BRANCH}"
git checkout -f -B "${BRANCH}" "origin/${BRANCH}"

echo "[deploy] OK: \\$(git rev-parse --short HEAD) em ${BRANCH}"
git status --short | head -n 20 || true
'"