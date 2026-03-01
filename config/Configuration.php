<?php

/*
 * Classe abstrata para tratar das configura��es padr�o do sistema
 *
 */
namespace Configuration;

define("PATH_WEB", 'krypitonite');
define("PATH_APP", realpath(dirname(__DIR__)));

class Configuration
{
    // Configuration FOLDERS PROJECT
    const PATH_APPICATION   = PATH_APP;
    const PATH_FRAMEWORK    = self::PATH_APPICATION . '/krypitonite';
    const PATH_SOURCE       = self::PATH_APPICATION . '/src';
    const PATH_LOG          = self::PATH_APPICATION . '/data/logs';
    const PATH_UPLOADS          = self::PATH_APPICATION . '/data/uploads';
    const PATH_PEDIDO          = self::PATH_APPICATION . '/data/uploads/pedido';
    const PATH_PRODUTO          = self::PATH_APPICATION . '/data/products';
    const PATH_COMENTARIO          = self::PATH_APPICATION . '/data/comentarios';
    const PATH_MARCA          = self::PATH_APPICATION . '/data/marcas';
    const PATH_ETIQUETAS          = self::PATH_APPICATION . '/data/etiquetas';
    const PATH_BUILD          = self::PATH_APPICATION . '/public/build';
    const PATH_LIB          = self::PATH_APPICATION . '/lib';
    const PATH_DOC          = self::PATH_APPICATION . '/docs';
    const PATH_NOTA          = self::PATH_APPICATION . '/data/notas';
    const FILE_CERTIFICADO_DIGITAL_A1_GJS          = self::PATH_APPICATION . '/data/certificado_digital/GJS_EMPREENDEDORISMO_DIGITAL_LTDA_20747907000126_1582207958008045000.pfx';
    
    const MYSQL_HOST = '162.240.19.34'; // localhost
    const MYSQL_PORT = 3306;
    const MYSQL_USER = 'evamodamodesta_user'; // root
    const MYSQL_PASS = '1995179ati'; // 1995179
    const MYSQL_DB = 'evamodamodesta_db';  // eva

    private static function envValue($name, $default)
    {
        $value = getenv($name);
        if ($value === false || $value === '') {
            return $default;
        }

        return $value;
    }
    
    // Configurations MAIL
    const MAIL_USERNAME    = 'contato@agiliza.com.br';
    const MAIL_PASSWORD    = '1995179ada';
    const MAIL_PORT        = 587;
    const MAIL_HOST        = 'smtp.gmail.com';
    const MAIL_SMTP_SECURE = 'tsl';
    const MAIL_SMTP_AUTH   = true;
    
    // Used Memcached
    const USED_MEMCACHED    = FALSE;
    
    /*
     * Parans conect database
     * */
    private static $connectionParams = [
        'dbname'   => self::MYSQL_DB,
        'user'     => self::MYSQL_USER,
        'password' => self::MYSQL_PASS,
        'host'     => self::MYSQL_HOST,
        'port'     => self::MYSQL_PORT,
        'driver'   => 'pdo_mysql'
    ];

    public static function getConnectionParams()
    {
        self::$connectionParams['dbname'] = self::envValue('MYSQL_DB', self::MYSQL_DB);
        self::$connectionParams['user'] = self::envValue('MYSQL_USER', self::MYSQL_USER);
        self::$connectionParams['password'] = self::envValue('MYSQL_PASS', self::MYSQL_PASS);
        self::$connectionParams['host'] = self::envValue('MYSQL_HOST', self::MYSQL_HOST);
        self::$connectionParams['port'] = (int) self::envValue('MYSQL_PORT', self::MYSQL_PORT);

        return self::$connectionParams;
    }
}

