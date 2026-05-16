#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "${ROOT_DIR}"

SSH_HOST="${SSH_HOST:-loterica}"
REMOTE_APP_DIR="${REMOTE_APP_DIR:-/home/evamodamodesta/public_html}"
DOCKER_DB_CONTAINER="${DOCKER_DB_CONTAINER:-evamodamodesta_db}"
LOCAL_DB_HOST="${LOCAL_DB_HOST:-127.0.0.1}"
LOCAL_DB_PORT="${LOCAL_DB_PORT:-3306}"
LOCAL_DB_NAME="${LOCAL_DB_NAME:-evamodamodesta_db}"
LOCAL_DB_USER="${LOCAL_DB_USER:-evamodamodesta_user}"
LOCAL_DB_PASS="${LOCAL_DB_PASS:-evamodamodesta_pass}"
LOCAL_DUMP_DIR="${LOCAL_DUMP_DIR:-/tmp}"
TIMESTAMP="$(date +%Y%m%d%H%M%S)"
LOCAL_SQL="${LOCAL_DUMP_DIR}/evamodamodesta_db_${TIMESTAMP}.sql"
LOCAL_SQL_GZ="${LOCAL_SQL}.gz"
REMOTE_SQL_GZ="${REMOTE_APP_DIR}/$(basename "${LOCAL_SQL_GZ}")"

require_cmd() {
  if ! command -v "$1" >/dev/null 2>&1; then
    echo "[erro] Comando obrigatorio nao encontrado: $1" >&2
    exit 1
  fi
}

require_cmd docker
require_cmd scp
require_cmd ssh
require_cmd gzip
require_cmd gunzip
require_cmd curl

if ! docker inspect "${DOCKER_DB_CONTAINER}" >/dev/null 2>&1; then
  echo "[erro] Container do MySQL nao encontrado: ${DOCKER_DB_CONTAINER}" >&2
  exit 1
fi

echo "[1/6] Gerando dump local em ${LOCAL_SQL_GZ}..."
docker exec "${DOCKER_DB_CONTAINER}" sh -lc "mysqldump \
  -h '${LOCAL_DB_HOST}' \
  -P '${LOCAL_DB_PORT}' \
  -u'${LOCAL_DB_USER}' \
  -p'${LOCAL_DB_PASS}' \
  --single-transaction \
  --routines \
  --triggers \
  --default-character-set=utf8mb4 \
  '${LOCAL_DB_NAME}'" > "${LOCAL_SQL}"
gzip -f "${LOCAL_SQL}"

if [[ ! -s "${LOCAL_SQL_GZ}" ]]; then
  echo "[erro] Dump local nao foi criado corretamente: ${LOCAL_SQL_GZ}" >&2
  exit 1
fi
ls -lh "${LOCAL_SQL_GZ}"

echo "[2/6] Enviando dump para ${SSH_HOST}:${REMOTE_SQL_GZ}..."
scp "${LOCAL_SQL_GZ}" "${SSH_HOST}:${REMOTE_SQL_GZ}"

echo "[3/6] Fazendo backup remoto e importando dump..."
ssh "${SSH_HOST}" "REMOTE_APP_DIR='${REMOTE_APP_DIR}' REMOTE_SQL_GZ='${REMOTE_SQL_GZ}' bash -s" <<'REMOTE_EOF'
set -euo pipefail

cd "${REMOTE_APP_DIR}"

if [[ ! -f config/Configuration.php ]]; then
  echo "[erro] Arquivo de configuracao nao encontrado em ${REMOTE_APP_DIR}/config/Configuration.php" >&2
  exit 1
fi

mapfile -t DBCONF < <(php -r '
require "config/Configuration.php";
echo \Configuration\Configuration::MYSQL_HOST, PHP_EOL;
echo \Configuration\Configuration::MYSQL_PORT, PHP_EOL;
echo \Configuration\Configuration::MYSQL_USER, PHP_EOL;
echo \Configuration\Configuration::MYSQL_PASS, PHP_EOL;
echo \Configuration\Configuration::MYSQL_DB, PHP_EOL;
')

if [[ "${#DBCONF[@]}" -ne 5 ]]; then
  echo "[erro] Nao foi possivel ler credenciais remotas via PHP." >&2
  exit 1
fi

R_HOST="${DBCONF[0]}"
R_PORT="${DBCONF[1]}"
R_USER="${DBCONF[2]}"
R_PASS="${DBCONF[3]}"
R_DB="${DBCONF[4]}"
R_TIMESTAMP="$(date +%Y%m%d%H%M%S)"
R_BACKUP="${REMOTE_APP_DIR}/backup_pre_import_${R_TIMESTAMP}.sql.gz"

echo "[remoto] Criando backup pre-import: ${R_BACKUP}"
mysqldump \
  -h "${R_HOST}" \
  -P "${R_PORT}" \
  -u"${R_USER}" \
  -p"${R_PASS}" \
  --single-transaction \
  --routines \
  --triggers \
  --default-character-set=utf8mb4 \
  "${R_DB}" | gzip > "${R_BACKUP}"

test -s "${R_BACKUP}"

echo "[remoto] Importando ${REMOTE_SQL_GZ} em ${R_DB}"
gunzip -c "${REMOTE_SQL_GZ}" | mysql \
  -h "${R_HOST}" \
  -P "${R_PORT}" \
  -u"${R_USER}" \
  -p"${R_PASS}" \
  "${R_DB}"

TABLE_COUNT="$(mysql -N -s -h "${R_HOST}" -P "${R_PORT}" -u"${R_USER}" -p"${R_PASS}" -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${R_DB}';")"
HAS_CONFIG="$(mysql -N -s -h "${R_HOST}" -P "${R_PORT}" -u"${R_USER}" -p"${R_PASS}" -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${R_DB}' AND table_name='configuracoes_plataforma';")"

echo "REMOTE_DB=${R_DB}"
echo "REMOTE_BACKUP=${R_BACKUP}"
echo "REMOTE_TABLE_COUNT=${TABLE_COUNT}"
echo "REMOTE_HAS_CONFIGURACOES_PLATAFORMA=${HAS_CONFIG}"
REMOTE_EOF

echo "[4/6] Validando resposta HTTP do site..."
HTTP_STATUS="$(curl -I -L -s -o /dev/null -w '%{http_code}' https://evamodamodesta.com.br)"
FINAL_URL="$(curl -I -L -s -o /dev/null -w '%{url_effective}' https://evamodamodesta.com.br)"
if [[ "${HTTP_STATUS}" != "200" && "${HTTP_STATUS}" != "301" && "${HTTP_STATUS}" != "302" ]]; then
  echo "[erro] Status HTTP inesperado: ${HTTP_STATUS} (${FINAL_URL})" >&2
  exit 1
fi

echo "[5/6] Validando home sem erro fatal nas primeiras linhas..."
HOME_HEAD="$(curl -sL https://evamodamodesta.com.br | head -n 20)"
if echo "${HOME_HEAD}" | grep -qi 'Fatal error'; then
  echo "[erro] Detectado Fatal error no HTML inicial da home." >&2
  exit 1
fi

echo "[6/6] Concluido com sucesso"
echo "LOCAL_DUMP=${LOCAL_SQL_GZ}"
echo "SITE_HTTP_STATUS=${HTTP_STATUS}"
echo "SITE_FINAL_URL=${FINAL_URL}"
