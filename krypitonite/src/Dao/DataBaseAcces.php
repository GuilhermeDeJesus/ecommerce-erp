<?php

/**
 * Created by Eclipse.
 * User: guilherme
 * Date: 13/03/2016
 * Time: 12:16
 */

namespace Krypitonite\dao;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use Configuration\Configuration as AplicationConfig;

class DataBaseAcces
{

	private static $_config;

    /**
     * Recupera conexão
     *
     * @return \Doctrine\DBAL\Connection
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function conn()
    {

        self::$_config = new Configuration();

        $conn = DriverManager::getConnection(AplicationConfig::getConnectionParams(),
                self::$_config);
        return $conn;

    }

}