# Agiliza

## Rodar local com Docker (PHP 8.0.30 + MySQL 5.7.44)

### Subir containers

```bash
docker compose up -d --build
```

Aplicação: http://localhost:8080  
MySQL: `127.0.0.1:3307` (db interna: `db:3306`)

### Credenciais locais do banco

- Database: `evamodamodesta_db`
- User: `evamodamodesta_user`
- Password: `evamodamodesta_pass`
- Root password: `root`

### Importar SQL local

Se houver dump em `public/sql`, ele será importado automaticamente na primeira inicialização do volume do MySQL.

Para forçar uma nova importação:

```bash
docker compose down -v
docker compose up -d --build
```

### Observação sobre .htaccess

O arquivo de produção `.htaccess` não foi alterado para o ambiente local.  
No Docker, é montado o arquivo `.docker/apache/.htaccess.local`, que mantém as regras de rota, mas remove o redirecionamento forçado para `https://www...` (evita conflito no localhost).
